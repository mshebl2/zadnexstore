import * as THREE from 'three';

export function initPerfume3D(canvasId = 'perfume-3d-canvas', containerId = 'perfume-3d-container') {
    const canvas = document.getElementById(canvasId);
    const container = document.getElementById(containerId);
    if (!canvas || !container) return null;

    // 1. Scene & Camera Setup
    const scene = new THREE.Scene();

    const width = container.clientWidth || 500;
    const height = container.clientHeight || 550;

    const camera = new THREE.PerspectiveCamera(36, width / height, 0.1, 100);
    camera.position.set(0, 0.5, 9.5);

    // 2. WebGL Renderer
    const renderer = new THREE.WebGLRenderer({
        canvas,
        alpha: true,
        antialias: true,
        powerPreference: 'high-performance'
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.25;

    // 3. Luxury Studio Lighting
    const ambientLight = new THREE.AmbientLight(0xfff8ee, 1.2);
    scene.add(ambientLight);

    const keyLight = new THREE.DirectionalLight(0xffe8ba, 2.5);
    keyLight.position.set(4, 6, 5);
    scene.add(keyLight);

    const rimLight = new THREE.DirectionalLight(0xb0d4ff, 2.0);
    rimLight.position.set(-5, 3, -4);
    scene.add(rimLight);

    const fillLight = new THREE.DirectionalLight(0xc5a059, 1.4);
    fillLight.position.set(0, -3, 4);
    scene.add(fillLight);

    // 4. Perfume Bottle Group
    const bottleGroup = new THREE.Group();
    bottleGroup.position.y = -0.5;
    scene.add(bottleGroup);

    // Glass Material (Physical Glass with Refraction)
    const glassMaterial = new THREE.MeshPhysicalMaterial({
        color: 0xffffff,
        metalness: 0.05,
        roughness: 0.06,
        transmission: 0.93,
        thickness: 1.1,
        ior: 1.52,
        transparent: true,
        opacity: 0.95,
        reflectivity: 0.8
    });

    // Glass Flacon Body (Rounded Beveled Luxury Shape)
    const glassGeo = new THREE.BoxGeometry(2.3, 3.4, 1.5, 4, 4, 4);
    const glassBody = new THREE.Mesh(glassGeo, glassMaterial);
    bottleGroup.add(glassBody);

    // Internal Amber Perfume Liquid
    const liquidGeo = new THREE.BoxGeometry(1.95, 2.85, 1.2);
    const liquidMat = new THREE.MeshStandardMaterial({
        color: 0xD4AF37,
        roughness: 0.2,
        metalness: 0.25,
        transparent: true,
        opacity: 0.82
    });
    const liquid = new THREE.Mesh(liquidGeo, liquidMat);
    liquid.position.y = -0.15;
    bottleGroup.add(liquid);

    // Inner Warm Glowing Light inside the perfume
    const internalGlow = new THREE.PointLight(0xffb84d, 1.6, 3.5);
    internalGlow.position.set(0, 0, 0);
    bottleGroup.add(internalGlow);

    // Gold Plaque on front face
    const plaqueGeo = new THREE.PlaneGeometry(1.4, 0.95);
    const goldPlaqueMat = new THREE.MeshStandardMaterial({
        color: 0xC5A059,
        roughness: 0.25,
        metalness: 0.9
    });
    const plaque = new THREE.Mesh(plaqueGeo, goldPlaqueMat);
    plaque.position.set(0, 0.1, 0.76);
    bottleGroup.add(plaque);

    // Gold Collar (Neck)
    const collarGeo = new THREE.CylinderGeometry(0.48, 0.52, 0.55, 32);
    const polishedGoldMat = new THREE.MeshStandardMaterial({
        color: 0xE2C485,
        roughness: 0.12,
        metalness: 0.96
    });
    const collar = new THREE.Mesh(collarGeo, polishedGoldMat);
    collar.position.y = 1.95;
    bottleGroup.add(collar);

    // Atomizer Spray Nozzle
    const nozzleGeo = new THREE.CylinderGeometry(0.32, 0.35, 0.45, 32);
    const nozzle = new THREE.Mesh(nozzleGeo, polishedGoldMat);
    nozzle.position.y = 2.35;
    bottleGroup.add(nozzle);

    // Royal Heavy Cap Group (The animated opening element)
    const capGroup = new THREE.Group();
    capGroup.position.set(0, 2.7, 0);
    bottleGroup.add(capGroup);

    // Cap Main Body (Octagonal royal crown facet)
    const capGeo = new THREE.CylinderGeometry(0.72, 0.72, 1.15, 8);
    const capMat = new THREE.MeshStandardMaterial({
        color: 0xC5A059,
        roughness: 0.14,
        metalness: 0.96
    });
    const capMesh = new THREE.Mesh(capGeo, capMat);
    capGroup.add(capMesh);

    // Cap Inlay Accent
    const accentGeo = new THREE.CylinderGeometry(0.45, 0.55, 0.2, 8);
    const accentMat = new THREE.MeshStandardMaterial({
        color: 0x242424,
        roughness: 0.2,
        metalness: 0.8
    });
    const accent = new THREE.Mesh(accentGeo, accentMat);
    accent.position.y = 0.65;
    capGroup.add(accent);

    // 5. Golden Fragrance Mist Particle System
    const particleCount = 140;
    const mistGeometry = new THREE.BufferGeometry();
    const mistPositions = new Float32Array(particleCount * 3);
    const mistVelocities = [];

    for (let i = 0; i < particleCount; i++) {
        mistPositions[i * 3] = 0;
        mistPositions[i * 3 + 1] = 2.45;
        mistPositions[i * 3 + 2] = 0;

        mistVelocities.push({
            x: (Math.random() - 0.5) * 0.04,
            y: 0.03 + Math.random() * 0.06,
            z: (Math.random() - 0.5) * 0.04,
            life: Math.random()
        });
    }

    mistGeometry.setAttribute('position', new THREE.BufferAttribute(mistPositions, 3));

    const mistMaterial = new THREE.PointsMaterial({
        color: 0xFFE082,
        size: 0.12,
        transparent: true,
        opacity: 0,
        blending: THREE.AdditiveBlending
    });

    const mistPoints = new THREE.Points(mistGeometry, mistMaterial);
    bottleGroup.add(mistPoints);

    // 6. Ethereal Orbit Rings
    const ringGeo = new THREE.TorusGeometry(2.4, 0.015, 16, 100);
    const ringMat = new THREE.MeshBasicMaterial({
        color: 0xC5A059,
        transparent: true,
        opacity: 0.3
    });
    const orbitRing1 = new THREE.Mesh(ringGeo, ringMat);
    orbitRing1.rotation.x = Math.PI / 2.5;
    bottleGroup.add(orbitRing1);

    const orbitRing2 = new THREE.Mesh(ringGeo, ringMat);
    orbitRing2.rotation.x = -Math.PI / 3;
    orbitRing2.rotation.y = Math.PI / 6;
    orbitRing2.scale.set(1.15, 1.15, 1.15);
    bottleGroup.add(orbitRing2);

    // State Variables
    let targetProgress = 0;
    let currentProgress = 0;
    let mouseX = 0;
    let mouseY = 0;
    let autoPlay = false;
    let autoPlayDirection = 1;

    // Mouse Parallax
    container.addEventListener('mousemove', (e) => {
        const rect = container.getBoundingClientRect();
        mouseX = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
        mouseY = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
    });

    container.addEventListener('mouseleave', () => {
        mouseX = 0;
        mouseY = 0;
    });

    // Scroll Synchronization Listener
    function updateFromScroll() {
        if (autoPlay) return;
        const rect = container.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        // When container enters top 80% of viewport to when it leaves bottom 20%
        const start = windowHeight * 0.85;
        const end = -rect.height * 0.5;
        const total = start - end;
        const current = start - rect.top;

        const p = Math.min(Math.max(current / total, 0), 1);
        targetProgress = p;
    }

    window.addEventListener('scroll', updateFromScroll, { passive: true });
    updateFromScroll();

    // Resize Handler
    function handleResize() {
        const w = container.clientWidth || 500;
        const h = container.clientHeight || 550;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h);
    }
    window.addEventListener('resize', handleResize);

    // Animation Loop
    let clock = new THREE.Clock();

    function animate() {
        requestAnimationFrame(animate);
        const delta = clock.getDelta();
        const time = clock.getElapsedTime();

        // Autoplay logic if active
        if (autoPlay) {
            targetProgress += 0.006 * autoPlayDirection;
            if (targetProgress >= 1) {
                targetProgress = 1;
                autoPlayDirection = -1;
            } else if (targetProgress <= 0) {
                targetProgress = 0;
                autoPlayDirection = 1;
            }
        }

        // Smooth Interpolation
        currentProgress += (targetProgress - currentProgress) * 0.07;

        // 1. Cap Lifting Animation (The reveal)
        // Resting y = 2.7. When fully open y = 5.2 (elevates by 2.5 units!)
        const capElevation = currentProgress * 2.6;
        capGroup.position.y = 2.7 + capElevation;
        capGroup.rotation.z = Math.sin(currentProgress * Math.PI) * 0.22;
        capGroup.rotation.y = time * 0.4 + currentProgress * 0.8;
        capGroup.position.x = Math.sin(currentProgress * Math.PI) * 0.25;

        // 2. Bottle 3D Rotation & Mouse Tilt
        const baseAngle = -0.3 + currentProgress * 0.8;
        bottleGroup.rotation.y = baseAngle + mouseX * 0.35 + Math.sin(time * 0.5) * 0.05;
        bottleGroup.rotation.x = -mouseY * 0.25 + Math.cos(time * 0.5) * 0.03;

        // Floating hover motion
        bottleGroup.position.y = -0.5 + Math.sin(time * 1.5) * 0.08;

        // Orbit rings rotation
        orbitRing1.rotation.z = time * 0.25;
        orbitRing2.rotation.z = -time * 0.2;

        // 3. Golden Mist Particle Spray Animation
        if (currentProgress > 0.3) {
            const mistIntensity = Math.min((currentProgress - 0.3) / 0.4, 1);
            mistMaterial.opacity = mistIntensity * (0.4 + Math.sin(time * 6) * 0.15);

            const posArray = mistGeometry.attributes.position.array;
            for (let i = 0; i < particleCount; i++) {
                const vel = mistVelocities[i];
                const idx = i * 3;

                posArray[idx] += vel.x * (1 + currentProgress);
                posArray[idx + 1] += vel.y;
                posArray[idx + 2] += vel.z * (1 + currentProgress);
                vel.life -= 0.015;

                // Reset particle to nozzle
                if (vel.life <= 0 || posArray[idx + 1] > 5.5) {
                    posArray[idx] = (Math.random() - 0.5) * 0.1;
                    posArray[idx + 1] = 2.45;
                    posArray[idx + 2] = (Math.random() - 0.5) * 0.1;
                    vel.life = 0.5 + Math.random() * 0.5;
                }
            }
            mistGeometry.attributes.position.needsUpdate = true;
        } else {
            mistMaterial.opacity = 0;
        }

        // Notify DOM of current progress & phase
        let phase = 1;
        if (currentProgress >= 0.75) phase = 4;
        else if (currentProgress >= 0.5) phase = 3;
        else if (currentProgress >= 0.25) phase = 2;

        const event = new CustomEvent('perfume3d-change', {
            detail: {
                progress: Math.round(currentProgress * 100),
                phase
            }
        });
        window.dispatchEvent(event);

        renderer.render(scene, camera);
    }

    animate();

    return {
        setProgress(val) {
            targetProgress = Math.max(0, Math.min(1, val));
        },
        toggleAutoPlay() {
            autoPlay = !autoPlay;
            return autoPlay;
        },
        destroy() {
            window.removeEventListener('scroll', updateFromScroll);
            window.removeEventListener('resize', handleResize);
            renderer.dispose();
        }
    };
}

