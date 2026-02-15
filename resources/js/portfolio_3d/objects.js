import * as THREE from 'three';

export function createProjectObject(project, index) {
    let geometry, material;

    switch (project.type_3d) {
        case 'planet_red':
            geometry = new THREE.SphereGeometry(2.5, 64, 64);
            material = new THREE.MeshStandardMaterial({
                color: 0xff3344,
                roughness: 0.2,
                metalness: 0.8,
                emissive: 0xaa2233,
                emissiveIntensity: 0.5
            });
            break;
        case 'planet_ring':
            const group = new THREE.Group();
            const planetGeom = new THREE.SphereGeometry(2.2, 64, 64);
            const planetMat = new THREE.MeshPhysicalMaterial({
                color: 0xeeddaa,
                roughness: 0.1,
                metalness: 0.4,
                emissive: 0x422200,
                emissiveIntensity: 0.2
            });
            group.add(new THREE.Mesh(planetGeom, planetMat));

            const ringGeom = new THREE.RingGeometry(3.5, 5, 128);
            const ringMat = new THREE.MeshBasicMaterial({
                color: 0xc084fc,
                transparent: true,
                opacity: 0.3,
                side: THREE.DoubleSide
            });
            const ring = new THREE.Mesh(ringGeom, ringMat);
            ring.rotation.x = Math.PI / 2.2;
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
            geometry = new THREE.BoxGeometry(1.5, 5.5, 1.5);
            material = new THREE.MeshPhysicalMaterial({
                color: 0x010101,
                roughness: 0,
                metalness: 1,
                clearcoat: 1.0,
                emissive: 0x7c3aed,
                emissiveIntensity: 0.4
            });
            break;
        case 'star_cluster':
            geometry = new THREE.OctahedronGeometry(2.5, 2);
            material = new THREE.MeshStandardMaterial({
                color: 0x06b6d4,
                emissive: 0x06b6d4,
                emissiveIntensity: 0.8,
                wireframe: true
            });
            break;
        case 'planet_blue':
        default:
            geometry = new THREE.SphereGeometry(2.5, 64, 64);
            material = new THREE.MeshPhysicalMaterial({
                color: 0x06b6d4,
                roughness: 0.05,
                metalness: 0.9,
                emissive: 0x004488,
                emissiveIntensity: 0.4
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
    canvas.height = 512;

    // Glowing Holographic Circle
    const centerX = 256;
    const centerY = 256;

    // Outer Ring
    ctx.strokeStyle = skill.color || '#a855f7';
    ctx.lineWidth = 4;
    ctx.beginPath();
    ctx.arc(centerX, centerY, 180, 0, Math.PI * 2);
    ctx.stroke();

    // Inner Glow
    const grd = ctx.createRadialGradient(centerX, centerY, 50, centerX, centerY, 200);
    grd.addColorStop(0, 'rgba(124, 58, 237, 0.4)');
    grd.addColorStop(1, 'rgba(124, 58, 237, 0)');
    ctx.fillStyle = grd;
    ctx.beginPath();
    ctx.arc(centerX, centerY, 180, 0, Math.PI * 2);
    ctx.fill();

    // Skill Name
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 50px Outfit, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText(skill.name.toUpperCase(), centerX, centerY);

    // Proficiency "Scan" text
    ctx.font = '24px monospace';
    ctx.fillStyle = skill.color || '#a855f7';
    ctx.fillText(`${skill.proficiency}% SYNCED`, centerX, centerY + 60);

    const texture = new THREE.CanvasTexture(canvas);
    const material = new THREE.SpriteMaterial({
        map: texture,
        transparent: true,
        blending: THREE.AdditiveBlending
    });
    const sprite = new THREE.Sprite(material);

    sprite.scale.set(6, 6, 1);

    const row = Math.floor(index / 3);
    const col = index % 3;

    sprite.position.x = (col - 1) * 8;
    sprite.position.y = (Math.random() - 0.5) * 6;
    sprite.position.z = -25 - (row * 6);

    sprite.userData = { isSkill: true };

    return sprite;
}
