import * as THREE from 'three';
import { gsap } from 'gsap';
import { initScene } from './scene';
import { createProjectObject, createSkillObject } from './objects';

// 1. Setup
const { scene, camera, renderer, starField1, starField2, galaxy } = initScene();
const projects = window.portfolioData.projects || [];
let currentScroll = 0;
const scrollLimit = 5000; // Total "virtual" scroll height for cinematic flight

// 2. Add Projects (Floating in the galaxy branches)
const projectMeshes = [];
projects.forEach((proj, index) => {
    const mesh = createProjectObject(proj, index + 1);
    // Disperse more along Z
    mesh.position.z = -index * 30 - 20;
    scene.add(mesh);
    projectMeshes.push(mesh);
});

// 2.5 Add Skills (Floating holographic field)
const skills = window.portfolioData.skills || [];
const skillMeshes = [];
skills.forEach((skill, index) => {
    const sprite = createSkillObject(skill, index);
    sprite.position.z = -300 - (index * 15); // Farther back for specific section
    scene.add(sprite);
    skillMeshes.push(sprite);
});

// 3. Interaction Logic
const raycaster = new THREE.Raycaster();
const mouse = new THREE.Vector2();
let hoveredProject = null;

function onMouseMove(event) {
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
}

function onMouseClick() {
    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(projectMeshes, true);

    if (intersects.length > 0) {
        let object = intersects[0].object;
        while (object && !object.userData.isProject) object = object.parent;
        if (object && object.userData.isProject) openProjectModal(object.userData.project);
    }
}

window.addEventListener('mousemove', onMouseMove);
window.addEventListener('click', onMouseClick);

// GSAP Cinematic Camera Flight
window.addEventListener('wheel', (e) => {
    currentScroll += e.deltaY;
    currentScroll = Math.max(0, Math.min(currentScroll, scrollLimit));

    // Convert scroll to camera position
    const progress = currentScroll / scrollLimit;

    gsap.to(camera.position, {
        z: 10 - (progress * 500), // Fly deep into space
        y: Math.sin(progress * Math.PI) * 20,
        x: Math.cos(progress * Math.PI) * 10,
        duration: 1.5,
        ease: "power2.out"
    });

    handleSectionTransitions(progress);
}, { passive: true });

function handleSectionTransitions(progress) {
    // Reveal UI based on progress
    const hero = document.getElementById('hero-content');
    const about = document.getElementById('about-section');

    if (progress < 0.05) {
        gsap.to(hero, { opacity: 1, y: 0, duration: 1 });
    } else {
        gsap.to(hero, { opacity: 0, y: -20, duration: 0.5 });
    }

    if (progress > 0.9) {
        about.style.pointerEvents = 'auto';
        gsap.to(about, { opacity: 1, scale: 1, duration: 1 });
    } else {
        about.style.pointerEvents = 'none';
        gsap.to(about, { opacity: 0, scale: 0.95, duration: 0.5 });
    }
}

// UI Functions
function openProjectModal(project) {
    const modal = document.getElementById('project-modal');
    const title = document.getElementById('p-title');
    const desc = document.getElementById('p-desc');
    const tech = document.getElementById('p-tech');
    const img = document.getElementById('p-image');
    const link = document.getElementById('p-link');
    const github = document.getElementById('p-github');

    title.innerText = project.title;
    desc.innerText = project.description || 'A unique digital masterpiece.';
    tech.innerText = (project.tech_stack && Array.isArray(project.tech_stack)) ? project.tech_stack.join(' / ') : 'TECH';

    if (project.thumbnail_url) {
        const isFullUrl = project.thumbnail_url.startsWith('http');
        img.src = isFullUrl ? project.thumbnail_url : `${window.portfolioData.assetPath}/${project.thumbnail_url}`;
        img.style.display = 'block';
    } else {
        img.style.display = 'none';
    }

    link.href = project.project_url || '#';
    github.href = project.github_url || '#';
    modal.classList.remove('translate-x-full');
}

document.getElementById('close-modal').addEventListener('click', () => {
    document.getElementById('project-modal').classList.add('translate-x-full');
});

// Quick Nav (Jump through space)
window.cameraTo = (section) => {
    let targetP = 0;
    if (section === 'projects') targetP = 0.15;
    if (section === 'skills') targetP = 0.6;
    if (section === 'about') targetP = 1.0;

    currentScroll = targetP * scrollLimit;
    gsap.to(camera.position, {
        z: 10 - (targetP * 500),
        duration: 2.5,
        ease: "power3.inOut"
    });
    handleSectionTransitions(targetP);
};

// 4. Animation Loop
function animate() {
    requestAnimationFrame(animate);

    const time = Date.now() * 0.0005;

    // Galaxy Animation
    galaxy.rotation.y += 0.001;
    galaxy.rotation.z += 0.0005;

    // Starfield Parallax
    starField1.rotation.y = Math.sin(time * 0.1) * 0.1;
    starField2.rotation.y = Math.cos(time * 0.1) * 0.1;

    // Subtle parallax shift based on mouse
    const targetCamX = mouse.x * 2;
    const targetCamY = mouse.y * 2;
    camera.position.x += (targetCamX - camera.position.x) * 0.02;
    camera.position.y += (targetCamY - camera.position.y) * 0.02;

    // Project Hover interaction
    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(projectMeshes, true);
    if (intersects.length > 0) {
        let object = intersects[0].object;
        while (object && !object.userData.isProject) object = object.parent;
        if (object) {
            if (hoveredProject !== object) hoveredProject = object;
            object.scale.lerp(new THREE.Vector3(1.2, 1.2, 1.2), 0.1);
            document.body.style.cursor = 'pointer';
        }
    } else {
        if (hoveredProject) hoveredProject.scale.lerp(new THREE.Vector3(1, 1, 1), 0.1);
        hoveredProject = null;
        document.body.style.cursor = 'default';
    }

    // Always look slightly ahead in space
    camera.lookAt(0, 0, camera.position.z - 50);

    renderer.render(scene, camera);
}

// Start
document.getElementById('loading-bar').style.width = '100%';
setTimeout(() => {
    gsap.to('#loading', {
        opacity: 0, duration: 1, onComplete: () => {
            document.getElementById('loading').style.display = 'none';
            animate();
            gsap.from('#hero-content', { opacity: 0, y: 100, duration: 2, ease: "power4.out" });
        }
    });
}, 1500);
