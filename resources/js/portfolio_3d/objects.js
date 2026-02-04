import * as THREE from 'three';

export function createProjectObject(project, index) {
    let geometry, material;

    // Determine shape/color based on type
    switch (project.type_3d) {
        case 'planet_red':
            geometry = new THREE.SphereGeometry(2, 32, 32);
            material = new THREE.MeshStandardMaterial({ 
                color: 0xff4444, 
                roughness: 0.7,
                metalness: 0.1 
            });
            break;
        case 'planet_ring':
            // Complex object could go here, for now simpler
            geometry = new THREE.SphereGeometry(1.8, 32, 32);
            material = new THREE.MeshStandardMaterial({ 
                color: 0xddddaa,
                emissive: 0x222211
            });
            break;
        case 'monolith':
            geometry = new THREE.BoxGeometry(1.5, 4, 1.5);
            material = new THREE.MeshStandardMaterial({ 
                color: 0x222222,
                roughness: 0.1,
                metalness: 0.9 
            });
            break;
        case 'star_cluster':
             geometry = new THREE.IcosahedronGeometry(2, 0);
             material = new THREE.MeshStandardMaterial({ 
                color: 0xffaa00,
                emissive: 0xff4400,
                wireframe: true
            });
            break;
        case 'planet_blue':
        default:
            geometry = new THREE.SphereGeometry(2, 32, 32);
            material = new THREE.MeshStandardMaterial({ 
                color: 0x0088ff,
                roughness: 0.5,
                metalness: 0.1
            });
            break;
    }

    const mesh = new THREE.Mesh(geometry, material);
    
    // Position projects along a path, spiraling
    const spacing = 15;
    const angle = index * 0.5;
    const radius = 5;

    mesh.position.z = -index * spacing;
    mesh.position.x = Math.cos(angle) * radius;
    mesh.position.y = Math.sin(angle) * radius;

    // Attach data to mesh for Raycaster
    mesh.userData = { 
        isProject: true, 
        project: project 
    };

    // ... existing code ...

    return mesh;
}

export function createSkillObject(skill, index) {
    // Create label using Canvas
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 256;
    canvas.height = 128; // Rectangular for text

    // Background
    ctx.fillStyle = skill.color || '#ffffff';
    ctx.globalAlpha = 0.2;
    ctx.roundRect(0, 0, 256, 128, 20);
    ctx.fill();
    
    // Border
    ctx.globalAlpha = 1.0;
    ctx.strokeStyle = skill.color || '#ffffff';
    ctx.lineWidth = 4;
    ctx.stroke();

    // Text
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 32px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(skill.name, 128, 48);
    
    // Proficiency Bar
    const profWidth = (skill.proficiency / 100) * 200;
    ctx.fillStyle = '#444444';
    ctx.fillRect(28, 90, 200, 10); // track
    ctx.fillStyle = skill.color || '#ffffff';
    ctx.fillRect(28, 90, profWidth, 10); // value

    const texture = new THREE.CanvasTexture(canvas);
    const material = new THREE.SpriteMaterial({ map: texture });
    const sprite = new THREE.Sprite(material);
    
    sprite.scale.set(3, 1.5, 1);

    // Arrange in a cloud/grid around Z = -25
    const row = Math.floor(index / 3);
    const col = index % 3;
    
    sprite.position.x = (col - 1) * 4;
    sprite.position.y = (Math.random() - 0.5) * 4; // Randomize height slightly
    sprite.position.z = -25 - (row * 3); // Start at -25

    return sprite;
}
