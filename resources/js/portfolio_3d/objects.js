import * as THREE from 'three';

export function createProjectObject(project, index) {
    let geometry, material;

    switch (project.type_3d) {
        case 'planet_red':
            geometry = new THREE.SphereGeometry(2.5, 64, 64);
            material = new THREE.MeshStandardMaterial({
                color: 0xff5533,
                roughness: 0.4,
                metalness: 0.3,
                emissive: 0xaa2200,
                emissiveIntensity: 0.2
            });
            break;
        case 'planet_ring':
            const group = new THREE.Group();
            const planetGeom = new THREE.SphereGeometry(2.2, 64, 64);
            const planetMat = new THREE.MeshStandardMaterial({
                color: 0xeeddaa,
                roughness: 0.8,
                metalness: 0.1
            });
            group.add(new THREE.Mesh(planetGeom, planetMat));

            const ringGeom = new THREE.RingGeometry(3, 5, 64);
            const ringMat = new THREE.MeshStandardMaterial({
                color: 0xffccaa,
                transparent: true,
                opacity: 0.4,
                side: THREE.DoubleSide
            });
            const ring = new THREE.Mesh(ringGeom, ringMat);
            ring.rotation.x = Math.PI / 2.5;
            group.add(ring);

            group.userData = { isProject: true, project: project };

            // Positioning for group
            const spacing = 15;
            const angle = index * 0.5;
            const radius = 6;
            group.position.z = -index * spacing;
            group.position.x = Math.cos(angle) * radius;
            group.position.y = Math.sin(angle) * radius;

            return group;

        case 'monolith':
            geometry = new THREE.BoxGeometry(1.5, 5, 1.5);
            material = new THREE.MeshPhysicalMaterial({
                color: 0x0a0a0a,
                roughness: 0.05,
                metalness: 0.9,
                clearcoat: 1.0,
                emissive: 0x06b6d4,
                emissiveIntensity: 0.1
            });
            break;
        case 'star_cluster':
            geometry = new THREE.IcosahedronGeometry(2.5, 1);
            material = new THREE.MeshStandardMaterial({
                color: 0xffaa00,
                emissive: 0xff8800,
                emissiveIntensity: 0.5,
                wireframe: true
            });
            break;
        case 'planet_blue':
        default:
            geometry = new THREE.SphereGeometry(2.5, 64, 64);
            material = new THREE.MeshStandardMaterial({
                color: 0x3b82f6,
                roughness: 0.2,
                metalness: 0.4,
                emissive: 0x001133,
                emissiveIntensity: 0.3
            });
            break;
    }

    const mesh = new THREE.Mesh(geometry, material);

    // Position projects along a path, spiraling
    const spacing = 15;
    const angle = index * 0.5;
    const radius = 6;

    mesh.position.z = -index * spacing;
    mesh.position.x = Math.cos(angle) * radius;
    mesh.position.y = Math.sin(angle) * radius;
    mesh.rotation.y = Math.random() * Math.PI;

    mesh.userData = {
        isProject: true,
        project: project
    };

    return mesh;
}

export function createSkillObject(skill, index) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 512;
    canvas.height = 256;

    // Glowing Holographic background
    const gradient = ctx.createLinearGradient(0, 0, 512, 0);
    gradient.addColorStop(0, 'rgba(6, 182, 212, 0)');
    gradient.addColorStop(0.5, 'rgba(6, 182, 212, 0.2)');
    gradient.addColorStop(1, 'rgba(6, 182, 212, 0)');

    ctx.fillStyle = gradient;
    ctx.fillRect(0, 40, 512, 176);

    // Top/Bottom lines
    ctx.strokeStyle = skill.color || '#06b6d4';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(100, 40); ctx.lineTo(412, 40);
    ctx.moveTo(100, 216); ctx.lineTo(412, 216);
    ctx.stroke();

    // Text
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 64px Outfit, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(skill.name.toUpperCase(), 256, 100);

    // Data readout feel
    ctx.font = '20px monospace';
    ctx.fillStyle = skill.color || '#06b6d4';
    ctx.fillText(`LYR:01 // AUTH:PRM // ${skill.proficiency}%`, 256, 160);

    const texture = new THREE.CanvasTexture(canvas);
    const material = new THREE.SpriteMaterial({
        map: texture,
        transparent: true,
        blending: THREE.AdditiveBlending
    });
    const sprite = new THREE.Sprite(material);

    sprite.scale.set(6, 3, 1);

    const row = Math.floor(index / 3);
    const col = index % 3;

    sprite.position.x = (col - 1) * 8;
    sprite.position.y = (Math.random() - 0.5) * 6;
    sprite.position.z = -25 - (row * 6);

    return sprite;
}
