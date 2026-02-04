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

    return mesh;
}
