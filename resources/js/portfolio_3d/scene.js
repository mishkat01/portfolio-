import * as THREE from 'three';

export function initScene() {
    const scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0x030014, 0.001);

    const camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.1, 4000);
    camera.position.z = 10;

    const renderer = new THREE.WebGLRenderer({
        alpha: true,
        antialias: true,
        powerPreference: "high-performance"
    });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.5;
    document.getElementById('canvas-container').appendChild(renderer.domElement);

    // Deep Space Lighting
    const ambientLight = new THREE.AmbientLight(0x4040ff, 0.4);
    scene.add(ambientLight);

    const mainLight = new THREE.PointLight(0x7c3aed, 3, 200); // Purple hub
    mainLight.position.set(0, 0, 0);
    scene.add(mainLight);

    // Deep Space Stars (Layered)
    function createStars(count, size, colorRange) {
        const geometry = new THREE.BufferGeometry();
        const vertices = [];
        const colors = [];
        for (let i = 0; i < count; i++) {
            const x = (Math.random() - 0.5) * 4000;
            const y = (Math.random() - 0.5) * 4000;
            const z = (Math.random() - 0.5) * 4000;
            vertices.push(x, y, z);
            const color = new THREE.Color(colorRange[Math.floor(Math.random() * colorRange.length)]);
            colors.push(color.r, color.g, color.b);
        }
        geometry.setAttribute('position', new THREE.Float32BufferAttribute(vertices, 3));
        geometry.setAttribute('color', new THREE.Float32BufferAttribute(colors, 3));
        return new THREE.Points(geometry, new THREE.PointsMaterial({
            size,
            vertexColors: true,
            transparent: true,
            opacity: 0.8,
            sizeAttenuation: true
        }));
    }

    const starField1 = createStars(10000, 0.7, [0xffffff, 0x818cf8, 0xc084fc]);
    const starField2 = createStars(5000, 1.2, [0xffffff, 0x06b6d4]);
    scene.add(starField1, starField2);

    // Galaxy Core (Centerpiece)
    const galaxyGeometry = new THREE.BufferGeometry();
    const galaxyVertices = [];
    const galaxyColors = [];
    const galaxyParticles = 15000;

    for (let i = 0; i < galaxyParticles; i++) {
        const i3 = i * 3;
        const radius = Math.random() * 20;
        const spinAngle = radius * 5;
        const branchAngle = (i % 3) * Math.PI * 2 / 3;

        const randomX = Math.pow(Math.random(), 3) * (Math.random() < 0.5 ? 1 : -1) * 0.3 * radius;
        const randomY = Math.pow(Math.random(), 3) * (Math.random() < 0.5 ? 1 : -1) * 0.3 * radius;
        const randomZ = Math.pow(Math.random(), 3) * (Math.random() < 0.5 ? 1 : -1) * 0.3 * radius;

        galaxyVertices.push(
            Math.cos(spinAngle + branchAngle) * radius + randomX,
            randomY,
            Math.sin(spinAngle + branchAngle) * radius + randomZ
        );

        const mixedColor = new THREE.Color(0x7c3aed).lerp(new THREE.Color(0x06b6d4), radius / 20);
        galaxyColors.push(mixedColor.r, mixedColor.g, mixedColor.b);
    }

    galaxyGeometry.setAttribute('position', new THREE.Float32BufferAttribute(galaxyVertices, 3));
    galaxyGeometry.setAttribute('color', new THREE.Float32BufferAttribute(galaxyColors, 3));

    const galaxyMaterial = new THREE.PointsMaterial({
        size: 0.15,
        sizeAttenuation: true,
        depthWrite: false,
        blending: THREE.AdditiveBlending,
        vertexColors: true
    });

    const galaxy = new THREE.Points(galaxyGeometry, galaxyMaterial);
    galaxy.position.z = -10;
    scene.add(galaxy);

    // Resize Handler
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    return { scene, camera, renderer, starField1, starField2, galaxy };
}
