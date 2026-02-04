import * as THREE from 'three';
import { initScene } from './scene';
import { createProjectObject } from './objects';

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

// 3. Interaction Logic
const raycaster = new THREE.Raycaster();
const mouse = new THREE.Vector2();

function onMouseClick(event) {
    // Calculate mouse position in normalized device coordinates
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(projectMeshes);

    if (intersects.length > 0) {
        const object = intersects[0].object;
        if (object.userData.isProject) {
            openProjectModal(object.userData.project);
        }
    }
}
window.addEventListener('click', onMouseClick);

// Scroll Handling
let scrollY = 0;
window.addEventListener('wheel', (e) => {
    targetCameraZ -= e.deltaY * 0.05;
    // Limit scroll
    const maxZ = 10;
    const minZ = -(projects.length * 15) - 10;
    targetCameraZ = Math.max(minZ, Math.min(maxZ, targetCameraZ));
});

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
    desc.innerText = project.description || 'No description available.';
    
    if (project.tech_stack && Array.isArray(project.tech_stack)) {
        tech.innerText = project.tech_stack.join(' • ');
    } else {
        tech.innerText = '';
    }

    if (project.thumbnail_url) {
        img.src = project.thumbnail_url;
        img.classList.remove('hidden');
    } else {
        img.classList.add('hidden');
    }

    link.href = project.project_url || '#';
    github.href = project.github_url || '#';

    modal.classList.remove('hidden');
    // small delay to allow transition
    setTimeout(() => {
        modal.classList.remove('translate-x-full');
    }, 10);
    
    // Stop responding to 3D clicks while modal is open? 
    // Maybe just putting it on top (z-index) is enough since we use pointer-events
}

document.getElementById('close-modal').addEventListener('click', () => {
    const modal = document.getElementById('project-modal');
    modal.classList.add('translate-x-full');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 500);
});

// Camera Quick Nav
window.cameraTo = (section) => {
    if (section === 'projects') targetCameraZ = -15;
    if (section === 'skills') targetCameraZ = -20; // Placeholder
    // ...
};

// 4. Animation Loop
function animate() {
    requestAnimationFrame(animate);

    // Smooth Camera Movement
    camera.position.z += (targetCameraZ - camera.position.z) * 0.05;

    // Rotate Stars
    starField.rotation.z += 0.0005;

    // Rotate Projects
    projectMeshes.forEach(mesh => {
        mesh.rotation.x += 0.005;
        mesh.rotation.y += 0.005;
    });

    renderer.render(scene, camera);
}

// Hide loading screen
document.getElementById('loading-bar').style.width = '100%';
setTimeout(() => {
    const loading = document.getElementById('loading');
    loading.style.opacity = '0';
    setTimeout(() => {
        loading.style.display = 'none';
        animate();
    }, 1000);
}, 500);

