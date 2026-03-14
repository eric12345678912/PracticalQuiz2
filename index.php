<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Summer Garden Scene</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { overflow: hidden; background: #000; }
  canvas { display: block; }
  #label {
    position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%);
    color: rgba(255,255,255,0.7); font-family: Georgia, serif; font-size: 13px;
    letter-spacing: 2px; text-transform: uppercase; pointer-events: none;
  }
</style>
</head>
<body>
<div id="label">Summer Garden Scene &mdash; Computer Graphics (No Models)</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>

// =============================================================================
// SCENE SETUP
// =============================================================================
const scene = new THREE.Scene();
scene.background = new THREE.Color(0x87CEEB);
scene.fog = new THREE.Fog(0xB0DFF7, 30, 80);

const camera = new THREE.PerspectiveCamera(55, innerWidth / innerHeight, 0.1, 200);
camera.position.set(0, 4, 14);
camera.lookAt(0, 1, 0);

const renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setSize(innerWidth, innerHeight);
renderer.shadowMap.enabled = true;
renderer.shadowMap.type = THREE.PCFSoftShadowMap;
document.body.appendChild(renderer.domElement);

// =============================================================================
// LIGHTING
// =============================================================================
const ambientLight = new THREE.AmbientLight(0xffeedd, 0.6);
scene.add(ambientLight);

const sunLight = new THREE.DirectionalLight(0xfff5cc, 1.4);
sunLight.position.set(10, 20, 10);
sunLight.castShadow = true;
sunLight.shadow.mapSize.set(2048, 2048);
sunLight.shadow.camera.near = 0.5;
sunLight.shadow.camera.far = 80;
sunLight.shadow.camera.left = -20; sunLight.shadow.camera.right = 20;
sunLight.shadow.camera.top = 20;  sunLight.shadow.camera.bottom = -20;
scene.add(sunLight);

const hemiLight = new THREE.HemisphereLight(0xB0DFF7, 0x228B22, 0.4);
scene.add(hemiLight);

// =============================================================================
// MATERIALS
// =============================================================================
const matGrass      = new THREE.MeshLambertMaterial({ color: 0x2e8b2e });
const matPath       = new THREE.MeshLambertMaterial({ color: 0xd2b48c });
const matDarkWood   = new THREE.MeshLambertMaterial({ color: 0x5C3317 });
const matChair      = new THREE.MeshPhongMaterial({ color: 0xA0522D, shininess: 40 });
const matTable      = new THREE.MeshPhongMaterial({ color: 0x6B3A2A, shininess: 60 });
const matMiloTin    = new THREE.MeshPhongMaterial({ color: 0x8B1A1A, shininess: 80 });
const matMiloLbl    = new THREE.MeshPhongMaterial({ color: 0xFFD700, shininess: 40 });
const matMilkTin    = new THREE.MeshPhongMaterial({ color: 0xE8E8E8, shininess: 90 });
const matMilkLbl    = new THREE.MeshPhongMaterial({ color: 0x1565C0, shininess: 40 });
const matCup        = new THREE.MeshPhongMaterial({ color: 0xFFFAF0, shininess: 100 });
const matCupRim     = new THREE.MeshPhongMaterial({ color: 0xCCCCCC, shininess: 120 });
const matSaucer     = new THREE.MeshPhongMaterial({ color: 0xFFFAF0, shininess: 100 });
const matTablecloth = new THREE.MeshPhongMaterial({ color: 0xFFF8DC, shininess: 20 });
const matPlate      = new THREE.MeshPhongMaterial({ color: 0xF5F5DC, shininess: 80 });
const matFood       = new THREE.MeshPhongMaterial({ color: 0xD2691E });
const matGazeboWall = new THREE.MeshPhongMaterial({ color: 0x55DD88, transparent: true, opacity: 0.6, side: THREE.DoubleSide });
const matRoof       = new THREE.MeshPhongMaterial({ color: 0x44CC66, shininess: 60 });
const matFloor      = new THREE.MeshLambertMaterial({ color: 0x556B55 });
const matGravel     = new THREE.MeshLambertMaterial({ color: 0xBBAA99 });
const matStairs     = new THREE.MeshLambertMaterial({ color: 0xAA9977 });
const matFlowers    = [
  new THREE.MeshPhongMaterial({ color: 0xFF6699 }),
  new THREE.MeshPhongMaterial({ color: 0xFF9900 }),
  new THREE.MeshPhongMaterial({ color: 0xFF4444 }),
  new THREE.MeshPhongMaterial({ color: 0xFFFF44 }),
];
const matStem       = new THREE.MeshLambertMaterial({ color: 0x228822 });
const matBird       = new THREE.MeshPhongMaterial({ color: 0x334455, shininess: 30 });
const matButterfly1 = new THREE.MeshPhongMaterial({ color: 0xFF8C00, side: THREE.DoubleSide });
const matButterfly2 = new THREE.MeshPhongMaterial({ color: 0xFF4500, side: THREE.DoubleSide });
const matFountain   = new THREE.MeshPhongMaterial({ color: 0xAABBCC, shininess: 100 });
const matWater      = new THREE.MeshPhongMaterial({ color: 0x4499CC, transparent: true, opacity: 0.7, shininess: 150 });
const matBench      = new THREE.MeshPhongMaterial({ color: 0x7B6347, shininess: 30 });
const matHedge      = new THREE.MeshLambertMaterial({ color: 0x1A7A1A });

// =============================================================================
// PRIMITIVE HELPER FUNCTIONS
// =============================================================================
function box(w, h, d, mat, x, y, z, rx=0, ry=0, rz=0) {
  const m = new THREE.Mesh(new THREE.BoxGeometry(w, h, d), mat);
  m.position.set(x, y, z); m.rotation.set(rx, ry, rz);
  m.castShadow = true; m.receiveShadow = true;
  scene.add(m); return m;
}
function cyl(rt, rb, h, seg, mat, x, y, z, rx=0, ry=0, rz=0) {
  const m = new THREE.Mesh(new THREE.CylinderGeometry(rt, rb, h, seg), mat);
  m.position.set(x, y, z); m.rotation.set(rx, ry, rz);
  m.castShadow = true; m.receiveShadow = true;
  scene.add(m); return m;
}
function sphere(r, ws, hs, mat, x, y, z) {
  const m = new THREE.Mesh(new THREE.SphereGeometry(r, ws, hs), mat);
  m.position.set(x, y, z);
  m.castShadow = true; m.receiveShadow = true;
  scene.add(m); return m;
}
function cone(r, h, seg, mat, x, y, z, ry=0) {
  const m = new THREE.Mesh(new THREE.ConeGeometry(r, h, seg), mat);
  m.position.set(x, y, z); m.rotation.y = ry;
  m.castShadow = true; m.receiveShadow = true;
  scene.add(m); return m;
}
function torus(r, tube, rs, ts, mat, x, y, z, rx=0) {
  const m = new THREE.Mesh(new THREE.TorusGeometry(r, tube, rs, ts), mat);
  m.position.set(x, y, z); m.rotation.x = rx;
  m.castShadow = true; m.receiveShadow = true;
  scene.add(m); return m;
}

// =============================================================================
// GROUND & TERRAIN
// =============================================================================
const ground = new THREE.Mesh(new THREE.PlaneGeometry(100, 100, 20, 20), matGrass);
ground.rotation.x = -Math.PI / 2; ground.receiveShadow = true;
scene.add(ground);

const ringGeo = new THREE.RingGeometry(4.2, 5.5, 48);
const gravel = new THREE.Mesh(ringGeo, matGravel);
gravel.rotation.x = -Math.PI / 2; gravel.position.y = 0.01;
scene.add(gravel);

for (let i = 0; i < 8; i++) box(1.2, 0.05, 0.6, matPath, 0, 0.02, 5.8 + i * 0.9);

// =============================================================================
// GAZEBO
// =============================================================================
cyl(4.2, 4.2, 0.18, 48, matFloor, 0, 0.09, 0);
box(1.4, 0.12, 0.4, matStairs, 0, 0.06, 4.2);
box(1.2, 0.22, 0.4, matStairs, 0, 0.11, 4.55);
box(1.0, 0.32, 0.4, matStairs, 0, 0.16, 4.9);

const wallGeo = new THREE.CylinderGeometry(3.85, 3.85, 2.2, 48, 1, true, 0, Math.PI * 2);
const wall = new THREE.Mesh(wallGeo, matGazeboWall);
wall.position.set(0, 1.29, 0); scene.add(wall);

for (let i = 0; i < 10; i++) {
  const angle = (i / 10) * Math.PI * 2;
  cyl(0.1, 0.1, 2.4, 8, matDarkWood, Math.sin(angle) * 3.75, 1.38, Math.cos(angle) * 3.75);
}

cone(4.2, 2.8, 48, matRoof, 0, 3.8, 0);
torus(3.8, 0.12, 8, 48, matDarkWood, 0, 2.42, 0, Math.PI / 2);
sphere(0.25, 8, 8, matDarkWood, 0, 5.3, 0);

// =============================================================================
// TABLE  (tabletop surface = Y 1.49)
// =============================================================================
cyl(1.05, 1.05, 0.04, 32, matTablecloth, 0, 1.47, 0);
cyl(1.0,  1.0,  0.08, 32, matTable,      0, 1.45, 0);
cyl(0.1,  0.1,  1.28, 12, matDarkWood,   0, 0.75, 0);
box(1.0, 0.08, 0.12, matDarkWood, 0, 0.10, 0);
box(0.12, 0.08, 1.0, matDarkWood, 0, 0.10, 0);
for (let i = 0; i < 4; i++) {
  const a = (i / 4) * Math.PI * 2;
  sphere(0.07, 6, 6, matDarkWood, Math.sin(a) * 0.45, 0.05, Math.cos(a) * 0.45);
}

// =============================================================================
// CHAIRS  (left & right)
// =============================================================================
function makeChair(cx, cz, ry) {
  const g = new THREE.Group(); scene.add(g);
  g.position.set(cx, 0, cz); g.rotation.y = ry;

  const seat = new THREE.Mesh(new THREE.BoxGeometry(0.6, 0.06, 0.56), matChair);
  seat.position.set(0, 0.8, 0); seat.castShadow = true; g.add(seat);

  const cushion = new THREE.Mesh(
    new THREE.BoxGeometry(0.54, 0.07, 0.5),
    new THREE.MeshPhongMaterial({ color: 0xFFE4B5, shininess: 20 })
  );
  cushion.position.set(0, 0.845, 0); cushion.castShadow = true; g.add(cushion);

  const back = new THREE.Mesh(new THREE.BoxGeometry(0.6, 0.5, 0.05), matChair);
  back.position.set(0, 1.07, -0.255); back.castShadow = true; g.add(back);

  for (let s = -1; s <= 1; s++) {
    const sl = new THREE.Mesh(new THREE.BoxGeometry(0.06, 0.46, 0.04), matDarkWood);
    sl.position.set(s * 0.2, 1.07, -0.25); sl.castShadow = true; g.add(sl);
  }

  [[-0.24, 0.24], [0.24, 0.24], [-0.24, -0.24], [0.24, -0.24]].forEach(([lx, lz]) => {
    const leg = new THREE.Mesh(new THREE.CylinderGeometry(0.035, 0.035, 0.78, 8), matChair);
    leg.position.set(lx, 0.39, lz); leg.castShadow = true; g.add(leg);
  });

  const strF = new THREE.Mesh(new THREE.CylinderGeometry(0.02, 0.02, 0.48, 8), matChair);
  strF.position.set(0, 0.38, 0.24); strF.rotation.z = Math.PI / 2; g.add(strF);
  const strB = new THREE.Mesh(new THREE.CylinderGeometry(0.02, 0.02, 0.48, 8), matChair);
  strB.position.set(0, 0.38, -0.24); strB.rotation.z = Math.PI / 2; g.add(strB);
  return g;
}

makeChair(-1.6, 0.0,  Math.PI * 0.5);
makeChair( 1.6, 0.0, -Math.PI * 0.5);

// =============================================================================
// TABLE ITEMS
// =============================================================================
cyl(0.115, 0.105, 0.38, 24, matMiloTin, -0.38, 1.68,  0.12);
cyl(0.118, 0.108, 0.22, 24, matMiloLbl, -0.38, 1.67,  0.12);
cyl(0.12,  0.12,  0.03, 24, matDarkWood,-0.38, 1.885, 0.12);
const miloLabel = new THREE.Mesh(
  new THREE.CylinderGeometry(0.119, 0.109, 0.08, 24, 1, true),
  new THREE.MeshPhongMaterial({ color: 0x006400 })
);
miloLabel.position.set(-0.38, 1.69, 0.12); scene.add(miloLabel);

cyl(0.1,   0.095, 0.34, 24, matMilkTin, 0.38, 1.66,  0.12);
cyl(0.103, 0.098, 0.2,  24, matMilkLbl, 0.38, 1.655, 0.12);
cyl(0.105, 0.105, 0.03, 24, matCupRim,  0.38, 1.845, 0.12);
const milkStripe = new THREE.Mesh(
  new THREE.CylinderGeometry(0.104, 0.099, 0.04, 24, 1, true),
  new THREE.MeshPhongMaterial({ color: 0xCC2222 })
);
milkStripe.position.set(0.38, 1.67, 0.12); scene.add(milkStripe);

function makeCup(cx, cz) {
  cyl(0.16,  0.14,  0.025, 24, matSaucer, cx, 1.5025, cz);
  cyl(0.08,  0.065, 0.12,  24, matCup,    cx, 1.575,  cz);
  torus(0.08, 0.009, 6, 20, matCupRim, cx, 1.635, cz, Math.PI / 2);
  cyl(0.072, 0.072, 0.01, 20,
    new THREE.MeshPhongMaterial({ color: 0x3B1A0A, shininess: 80 }), cx, 1.630, cz);
  const hGeo = new THREE.TorusGeometry(0.045, 0.012, 6, 12, Math.PI);
  const handle = new THREE.Mesh(hGeo, matCup);
  handle.position.set(cx + 0.09, 1.578, cz);
  handle.rotation.y = Math.PI / 2; handle.rotation.z = Math.PI / 2;
  handle.castShadow = true; scene.add(handle);
}
makeCup(-0.38, -0.38);
makeCup( 0.38, -0.38);

cyl(0.18, 0.16, 0.018, 20, matPlate, 0, 1.499, 0.38);
for (let i = 0; i < 3; i++) {
  const a = (i / 3) * Math.PI * 2;
  cyl(0.055, 0.05, 0.025, 12, matFood,
    Math.sin(a) * 0.1, 1.5245, 0.38 + Math.cos(a) * 0.1);
}

cyl(0.07,  0.065, 0.08, 16, matCup,    0, 1.53,  -0.38);
sphere(0.058, 8, 8, matCup,            0, 1.588, -0.38);
cyl(0.018, 0.018, 0.02, 8, matCupRim,  0, 1.646, -0.38);

// =============================================================================
// GARDEN BENCHES
// =============================================================================
function makeBench(bx, bz, ry) {
  const g = new THREE.Group(); g.position.set(bx, 0, bz); g.rotation.y = ry; scene.add(g);
  const seat = new THREE.Mesh(new THREE.BoxGeometry(1.4, 0.06, 0.38), matBench);
  seat.position.y = 0.72; g.add(seat);
  const back = new THREE.Mesh(new THREE.BoxGeometry(1.4, 0.32, 0.05), matBench);
  back.position.set(0, 0.92, -0.165); g.add(back);
  [[-0.55, 0], [0.55, 0]].forEach(([lx]) => {
    const leg = new THREE.Mesh(new THREE.BoxGeometry(0.06, 0.7, 0.34), matDarkWood);
    leg.position.set(lx, 0.35, 0); g.add(leg);
  });
}
makeBench(-7, 2,  0.4);
makeBench( 7, 2, -0.4);

// =============================================================================
// HEDGES
// =============================================================================
for (let i = -4; i <= 4; i++) {
  box(0.9, 1.1, 0.9, matHedge,  i * 1.0 - 0.5, 0.55, -9);
  box(0.9, 1.1, 0.9, matHedge, -9, 0.55,  i * 1.0 - 0.5);
  box(0.9, 1.1, 0.9, matHedge,  9, 0.55,  i * 1.0 - 0.5);
}
for (let i = -4; i <= 4; i++) {
  sphere(0.52, 8, 8, matHedge,  i * 1.0 - 0.5, 1.12, -9);
  sphere(0.52, 8, 8, matHedge, -9, 1.12,  i * 1.0 - 0.5);
  sphere(0.52, 8, 8, matHedge,  9, 1.12,  i * 1.0 - 0.5);
}

// =============================================================================
// FLOWERS
// =============================================================================
const flowerPositions = [
  [-6,1.5],[-5,-3],[-7,-1],[5,2],[6,-2],[7,1],[-3,-7],[3,-7],[-8,4],[8,-4],
  [-2,8],[2,7],[-6,6],[6,5],[-4,4],[4,3],[-1,-8],[1,-6],[-9,2],[9,-3]
];
flowerPositions.forEach(([fx, fz], i) => {
  const h = 0.3 + Math.random() * 0.2;
  cyl(0.015, 0.015, h, 6, matStem, fx, h / 2, fz);
  sphere(0.08 + Math.random() * 0.04, 6, 6, matFlowers[i % 4], fx, h + 0.06, fz);
});

// =============================================================================
// FOUNTAIN
// =============================================================================
cyl(1.0,  1.0,  0.18, 24, matFountain, -7, 0.09, -4);
cyl(0.08, 0.08, 1.1,  12, matFountain, -7, 0.64, -4);
cyl(0.5,  0.5,  0.14, 24, matFountain, -7, 1.26, -4);
cyl(0.48, 0.48, 0.05, 24, matWater,    -7, 1.24, -4);
sphere(0.12, 8, 8, matWater, -7, 1.45, -4);
torus(0.9, 0.1, 8, 24, matFountain, -7, 0.19, -4, Math.PI / 2);

// =============================================================================
// LAMP POSTS
// =============================================================================
function makeLamp(lx, lz) {
  cyl(0.05, 0.06, 3.5, 8, matDarkWood, lx, 1.75, lz);
  cyl(0.04, 0.04, 0.6, 8, matDarkWood, lx + 0.28, 3.25, lz, 0, 0, 0.5);
  sphere(0.15, 8, 8,
    new THREE.MeshPhongMaterial({ color: 0xFFFF99, emissive: 0xFFFF33, emissiveIntensity: 0.6 }),
    lx + 0.48, 3.28, lz
  );
  const pl = new THREE.PointLight(0xFFFF88, 0.4, 8);
  pl.position.set(lx + 0.48, 3.28, lz); scene.add(pl);
}
makeLamp(-5,  3); makeLamp(5,  3);
makeLamp(-5, -3); makeLamp(5, -3);

// =============================================================================
// BUTTERFLIES
// =============================================================================
const butterflies = [];
for (let i = 0; i < 5; i++) {
  const g = new THREE.Group();
  const wing1 = new THREE.Mesh(new THREE.CircleGeometry(0.14, 6), matButterfly1);
  wing1.position.x = 0.12;
  const wing2 = wing1.clone(); wing2.material = matButterfly2; wing2.position.x = -0.12;
  g.add(wing1); g.add(wing2);
  g.position.set((Math.random() - 0.5) * 8, 2 + Math.random() * 1.5, (Math.random() - 0.5) * 8);
  butterflies.push(g); scene.add(g);
}

// =============================================================================
// BIRDS
// =============================================================================
const birds = [];
for (let i = 0; i < 4; i++) {
  const g = new THREE.Group();
  const body = new THREE.Mesh(new THREE.SphereGeometry(0.07, 6, 4), matBird); g.add(body);
  const w1 = new THREE.Mesh(new THREE.BoxGeometry(0.22, 0.02, 0.12), matBird);
  w1.position.x = 0.15; g.add(w1);
  const w2 = w1.clone(); w2.position.x = -0.15; g.add(w2);
  g.position.set((Math.random() - 0.5) * 16, 8 + Math.random() * 3, (Math.random() - 0.5) * 16);
  birds.push(g); scene.add(g);
}

// =============================================================================
// SKY DOME
// =============================================================================
const skyGeo = new THREE.SphereGeometry(90, 16, 8, 0, Math.PI * 2, 0, Math.PI / 2);
const skyMat = new THREE.MeshBasicMaterial({ color: 0x5BB8F5, side: THREE.BackSide });
scene.add(new THREE.Mesh(skyGeo, skyMat));

// =============================================================================
// SUN
// =============================================================================
const sunGroup = new THREE.Group();
scene.add(sunGroup);

const sunDisc = new THREE.Mesh(
  new THREE.SphereGeometry(3.2, 32, 32),
  new THREE.MeshBasicMaterial({ color: 0xFFE033 })
);
sunDisc.position.set(38, 34, -40);
sunGroup.add(sunDisc);

const sunGlow = new THREE.Mesh(
  new THREE.SphereGeometry(4.4, 32, 32),
  new THREE.MeshBasicMaterial({ color: 0xFFAA00, transparent: true, opacity: 0.18 })
);
sunGlow.position.copy(sunDisc.position);
sunGroup.add(sunGlow);

const rayMat = new THREE.MeshBasicMaterial({ color: 0xFFDD44, transparent: true, opacity: 0.55 });
for (let r = 0; r < 12; r++) {
  const angle = (r / 12) * Math.PI * 2;
  const ray = new THREE.Mesh(new THREE.BoxGeometry(0.35, 5.5, 0.08), rayMat);
  ray.position.set(
    sunDisc.position.x + Math.cos(angle) * 7.2,
    sunDisc.position.y + Math.sin(angle) * 7.2,
    sunDisc.position.z
  );
  ray.rotation.z = angle;
  sunGroup.add(ray);
}

// =============================================================================
// CLOUDS
// =============================================================================
function makeCloud(cx, cy, cz, scale) {
  const g = new THREE.Group();
  const mat = new THREE.MeshPhongMaterial({ color: 0xFFFFFF, transparent: true, opacity: 0.92, shininess: 10 });
  const puffs = [
    [0,    0,    0,   1.00],
    [1.8,  0.1,  0,   0.85],
    [-1.7, 0.0,  0,   0.78],
    [0.9,  0.7,  0,   0.72],
    [-0.8, 0.6,  0.3, 0.65],
    [0,    0,    1.0, 0.60],
    [1.5, -0.2,  0.5, 0.55],
    [-1.2,-0.1,  0.6, 0.50],
  ];
  puffs.forEach(([px, py, pz, ps]) => {
    const m = new THREE.Mesh(new THREE.SphereGeometry(ps * 2.2 * scale, 12, 10), mat);
    m.position.set(px * scale * 1.4, py * scale * 1.2, pz * scale);
    g.add(m);
  });
  g.position.set(cx, cy, cz);
  scene.add(g);
  return g;
}

const clouds = [
  makeCloud(-22, 28, -35, 1.4),
  makeCloud( 18, 32, -42, 1.1),
  makeCloud( 42, 22, -20, 1.0),
  makeCloud(-40, 25, -10, 1.2),
  makeCloud(  5, 35, -50, 0.9),
  makeCloud(-10, 26, -28, 0.75),
  makeCloud( 30, 30, -15, 1.05),
  makeCloud(-30, 29,  10, 0.85),
];

// =============================================================================
// ORBIT CONTROLS
// =============================================================================
const orbitTarget = new THREE.Vector3(0, 1.5, 0);
let spherical = { radius: 14, phi: Math.PI / 4, theta: 0 };
const MIN_RADIUS = 1.5, MAX_RADIUS = 40;
let isDragging = false, lastMouse = { x: 0, y: 0 };
let autoOrbit = true;

function applySpherical() {
  const s = spherical;
  camera.position.x = orbitTarget.x + s.radius * Math.sin(s.phi) * Math.sin(s.theta);
  camera.position.y = orbitTarget.y + s.radius * Math.cos(s.phi);
  camera.position.z = orbitTarget.z + s.radius * Math.sin(s.phi) * Math.cos(s.theta);
  camera.lookAt(orbitTarget);
}
applySpherical();

renderer.domElement.addEventListener('wheel', e => {
  e.preventDefault();
  autoOrbit = false;
  const delta = e.deltaY > 0 ? 1.08 : 0.93;
  spherical.radius = Math.min(MAX_RADIUS, Math.max(MIN_RADIUS, spherical.radius * delta));
  applySpherical();
}, { passive: false });

renderer.domElement.addEventListener('mousedown', e => {
  if (e.button === 0) { isDragging = true; autoOrbit = false; lastMouse = { x: e.clientX, y: e.clientY }; }
});
window.addEventListener('mousemove', e => {
  if (!isDragging) return;
  spherical.theta -= (e.clientX - lastMouse.x) * 0.005;
  spherical.phi = Math.max(0.08, Math.min(Math.PI / 2 - 0.02, spherical.phi + (e.clientY - lastMouse.y) * 0.005));
  lastMouse = { x: e.clientX, y: e.clientY };
  applySpherical();
});
window.addEventListener('mouseup', () => { isDragging = false; });

let lastTouches = null, lastPinchDist = null;
renderer.domElement.addEventListener('touchstart', e => {
  autoOrbit = false; lastTouches = e.touches;
  if (e.touches.length === 2)
    lastPinchDist = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
}, { passive: true });
renderer.domElement.addEventListener('touchmove', e => {
  if (e.touches.length === 1 && lastTouches) {
    spherical.theta -= (e.touches[0].clientX - lastTouches[0].clientX) * 0.005;
    spherical.phi = Math.max(0.08, Math.min(Math.PI / 2 - 0.02, spherical.phi + (e.touches[0].clientY - lastTouches[0].clientY) * 0.005));
    applySpherical();
  } else if (e.touches.length === 2 && lastPinchDist !== null) {
    const d = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY);
    spherical.radius = Math.min(MAX_RADIUS, Math.max(MIN_RADIUS, spherical.radius * (lastPinchDist / d)));
    lastPinchDist = d; applySpherical();
  }
  lastTouches = e.touches;
}, { passive: true });
renderer.domElement.addEventListener('touchend', () => { lastTouches = null; lastPinchDist = null; }, { passive: true });

const hud = document.createElement('div');
hud.style.cssText = 'position:absolute;top:14px;left:50%;transform:translateX(-50%);color:rgba(255,255,255,0.8);font-family:Georgia,serif;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;pointer-events:none;background:rgba(0,0,0,0.28);padding:5px 14px;border-radius:20px;transition:opacity 1.5s;';
hud.textContent = '🖱 Drag to rotate  •  Scroll to zoom  •  Pinch on mobile';
document.body.appendChild(hud);
setTimeout(() => { hud.style.opacity = '0'; }, 5000);

// =============================================================================
// ANIMATION LOOP
// =============================================================================
let t = 0;
function animate() {
  requestAnimationFrame(animate);
  t += 0.012;

  if (autoOrbit) { spherical.theta += 0.003; applySpherical(); }

  butterflies.forEach((b, i) => {
    b.position.x = Math.sin(t * 0.7 + i * 1.3) * 3;
    b.position.z = Math.cos(t * 0.5 + i * 2.1) * 3;
    b.children[0].rotation.y =  Math.sin(t * 4) * 0.5;
    b.children[1].rotation.y = -Math.sin(t * 4) * 0.5;
    b.rotation.y = Math.atan2(Math.cos(t * 0.7 + i * 1.3), -Math.sin(t * 0.5 + i * 2.1));
  });

  clouds.forEach((c, i) => {
    c.position.x += 0.012 + i * 0.002;
    if (c.position.x > 55) c.position.x = -55;
  });

  sunGroup.rotation.z = t * 0.12;
  sunGlow.material.opacity = 0.13 + Math.sin(t * 1.5) * 0.07;

  birds.forEach((b, i) => {
    b.position.x += Math.sin(t * 0.3 + i) * 0.02;
    b.position.z += Math.cos(t * 0.2 + i) * 0.02;
    b.children[1].rotation.z =  Math.sin(t * 3 + i) * 0.3;
    b.children[2].rotation.z = -Math.sin(t * 3 + i) * 0.3;
  });

  renderer.render(scene, camera);
}
animate();

window.addEventListener('resize', () => {
  camera.aspect = innerWidth / innerHeight;
  camera.updateProjectionMatrix();
  renderer.setSize(innerWidth, innerHeight);
});
</script>
</body>
</html>