const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");
const startBtn = document.getElementById("startBtn");

let player = { x: 50, y: 300, speed: 0, distance: 0 };
let gameRunning = false;
let lastKey = null;
let finishLine = 700;

function drawGround() {
  ctx.fillStyle = "#7cfc00";
  ctx.fillRect(0, 350, canvas.width, 50);
}

function drawPlayer() {
  ctx.fillStyle = "#d35400";
  ctx.fillRect(player.x, player.y - 50, 40, 50); // tubuh
  ctx.fillStyle = "#2c3e50";
  ctx.fillRect(player.x, player.y, 40, 10); // bakiak
}

function drawFinishLine() {
  ctx.fillStyle = "#ff0000";
  ctx.fillRect(finishLine, 0, 10, 350);
}

function draw() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  drawGround();
  drawFinishLine();
  drawPlayer();

  ctx.fillStyle = "#000";
  ctx.font = "18px Poppins";
  ctx.fillText("Jarak: " + Math.floor(player.distance) + " m", 20, 30);

  if (player.x >= finishLine - 40) {
    gameRunning = false;
    ctx.font = "32px Poppins";
    ctx.fillText("🎉 Kamu Menang! 🎉", 280, 200);
  }
}

function update() {
  if (!gameRunning) return;

  player.x += player.speed;
  player.distance += player.speed * 0.1;

  player.speed *= 0.98; // gesekan

  draw();
  requestAnimationFrame(update);
}

function handleKey(e) {
  if (!gameRunning) return;

  if (e.key === "a" && lastKey !== "a") {
    player.speed += 1.5;
    lastKey = "a";
  } else if (e.key === "d" && lastKey !== "d") {
    player.speed += 1.5;
    lastKey = "d";
  }
}

startBtn.addEventListener("click", () => {
  player = { x: 50, y: 300, speed: 0, distance: 0 };
  gameRunning = true;
  lastKey = null;
  draw();
  update();
});

document.addEventListener("keydown", handleKey);

draw();
