import * as THREE from 'three';
import { initScene } from './scene';
import { createProjectObject, createSkillObject } from './objects';

// 1. Setup
const { scene, camera, renderer, starField } = initScene();
const projects = window.portfolioData.projects || [];
let targetCameraZ = 5;

// 2. Add Projects
const projectMeshes = [];
projects.forEach((proj, index) => {
    // Offset index by 1 so the first project isn't right on top of camera
    const mesh = createProjectObject(proj, index + 1);
    scene.add(mesh);
    projectMeshes.push(mesh);
});

// 2.5 Add Skills
const skills = window.portfolioData.skills || [];
const skillMeshes = [];
skills.forEach((skill, index) => {
    const sprite = createSkillObject(skill, index);
    scene.add(sprite);
    skillMeshes.push(sprite);
});

// 3. Interaction Logic
const raycaster = new THREE.Raycaster();
const mouse = new THREE.Vector2();

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
        // Traverse up to find the group/mesh with userData
        while (object && !object.userData.isProject) {
            object = object.parent;
        }

        if (object && object.userData.isProject) {
            openProjectModal(object.userData.project);
        }
    }
}

window.addEventListener('mousemove', onMouseMove);
window.addEventListener('click', onMouseClick);

// Scroll Handling
let scrollY = 0;
window.addEventListener('wheel', (e) => {
    targetCameraZ -= e.deltaY * 0.03; // Slightly slower, more cinematic
    const maxZ = 12;
    const minZ = -55;
    targetCameraZ = Math.max(minZ, Math.min(maxZ, targetCameraZ));
}, { passive: true });

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

    tech.innerText = (project.tech_stack && Array.isArray(project.tech_stack))
        ? project.tech_stack.join(' / ')
        : 'CREATIVE TECH';

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

// Camera Quick Nav
window.cameraTo = (section) => {
    document.getElementById('project-modal').classList.add('translate-x-full');
    if (section === 'projects') targetCameraZ = -10;
    if (section === 'skills') targetCameraZ = -30;
    if (section === 'about') targetCameraZ = -50;
};

// 4. Animation Loop
function animate() {
    requestAnimationFrame(animate);

    // Smooth Camera Movement (Lerp)
    camera.position.z += (targetCameraZ - camera.position.z) * 0.08;
    camera.position.x += (mouse.x * 2 - camera.position.x) * 0.05; // Subtle parallax
    camera.position.y += (-mouse.y * 2 - camera.position.y) * 0.05;
    camera.lookAt(0, 0, targetCameraZ - 20);

    // Rotate Stars
    starField.rotation.z += 0.0003;
    starField.rotation.y += 0.0001;

    // Hover detection
    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(projectMeshes, true);

    if (intersects.length > 0) {
        let object = intersects[0].object;
        while (object && !object.userData.isProject) object = object.parent;

        if (object) {
            if (hoveredProject !== object) {
                if (hoveredProject) hoveredProject.scale.set(1, 1, 1);
                hoveredProject = object;
            }
            object.scale.lerp(new THREE.Vector3(1.15, 1.15, 1.15), 0.1);
            document.body.style.cursor = 'pointer';
        }
    } else {
        if (hoveredProject) hoveredProject.scale.set(1, 1, 1);
        hoveredProject = null;
        document.body.style.cursor = 'default';
    }

    checkSectionVisibility();

    // Rotate Projects
    projectMeshes.forEach(mesh => {
        mesh.rotation.y += 0.01;
        mesh.rotation.x += 0.005;
    });

    renderer.render(scene, camera);
}

// Global Loading Logic
document.getElementById('loading-bar').style.width = '100%';
setTimeout(() => {
    const loading = document.getElementById('loading');
    loading.style.opacity = '0';
    setTimeout(() => {
        loading.style.display = 'none';
        animate();
    }, 1000);
}, 1500);

function checkSectionVisibility() {
    const aboutSection = document.getElementById('about-section');
    if (!aboutSection) return;

    if (camera.position.z < -42) {
        aboutSection.classList.remove('opacity-0', 'pointer-events-none');
        aboutSection.querySelector('.max-w-xl').style.transform = 'scale(1)';
    } else {
        aboutSection.classList.add('opacity-0', 'pointer-events-none');
        aboutSection.querySelector('.max-w-xl').style.transform = 'scale(0.95)';
    }
}
