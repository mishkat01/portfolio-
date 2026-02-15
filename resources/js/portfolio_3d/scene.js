import * as THREE from 'three';

export function initScene() {
    const scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0x05050a, 0.0015);

    const camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.1, 3000);
    camera.position.z = 8;

    const renderer = new THREE.WebGLRenderer({
        alpha: true,
        antialias: true,
        powerPreference: "high-performance"
    });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ReinhardToneMapping;
    renderer.toneMappingExposure = 1.2;
    document.getElementById('canvas-container').appendChild(renderer.domElement);

    // Dynamic Lighting
    const ambientLight = new THREE.AmbientLight(0x404040, 0.8); // Soft white light
    scene.add(ambientLight);

    const mainLight = new THREE.PointLight(0x06b6d4, 2, 100);
    mainLight.position.set(10, 10, 15);
    scene.add(mainLight);

    const secondaryLight = new THREE.PointLight(0x3b82f6, 1.5, 100);
    secondaryLight.position.set(-10, -5, 10);
    scene.add(secondaryLight);

    // Cinematic Starfield
    const starsGeometry = new THREE.BufferGeometry();
    const starVertices = [];
    const starColors = [];
    const colorOptions = [0xffffff, 0x06b6d4, 0x3b82f6, 0x7dd3fc];

    for (let i = 0; i < 8000; i++) {
        const x = (Math.random() - 0.5) * 3000;
        const y = (Math.random() - 0.5) * 3000;
        const z = (Math.random() - 0.5) * 3000;
        starVertices.push(x, y, z);

        const color = new THREE.Color(colorOptions[Math.floor(Math.random() * colorOptions.length)]);
        starColors.push(color.r, color.g, color.b);
    }

    starsGeometry.setAttribute('position', new THREE.Float32BufferAttribute(starVertices, 3));
    starsGeometry.setAttribute('color', new THREE.Float32BufferAttribute(starColors, 3));

    const starsMaterial = new THREE.PointsMaterial({
        size: 0.8,
        vertexColors: true,
        transparent: true,
        opacity: 0.8,
        sizeAttenuation: true
    });

    const starField = new THREE.Points(starsGeometry, starsMaterial);
    scene.add(starField);

    // Resize Handler
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    return { scene, camera, renderer, starField };
}
