const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");
const startBtn = document.getElementById("startBtn");

let finishLine = 800;
let gameRunning = false;
let winner = null;

let players = {
  p1: { x: 50, y: 280, color: "#d35400", speed: 0, distance: 0, lastKey: null },
  p2: { x: 50, y: 340, color: "#2980b9", speed: 0, distance: 0, lastKey: null }
};

function drawGround() {
  ctx.fillStyle = "#7cfc00";
  ctx.fillRect(0, 370, canvas.width, 30);
}

function drawFinishLine() {
  ctx.fillStyle = "#ff0000";
  ctx.fillRect(finishLine, 0, 10, 400);
}

function drawPlayer(p) {
  ctx.fillStyle = p.color;
  ctx.fillRect(p.x, p.y - 40, 40, 40); // tubuh
  ctx.fillStyle = "#2c3e50";
  ctx.fillRect(p.x, p.y, 40, 10); // bakiak
}

function drawText() {
  ctx.fillStyle = "#000";
  ctx.font = "18px Poppins";
  ctx.fillText("Jarak P1: " + Math.floor(players.p1.distance) + " m", 20, 30);
  ctx.fillText("Jarak P2: " + Math.floor(players.p2.distance) + " m", 20, 55);

  if (winner) {
    ctx.font = "32px Poppins";
    ctx.fillStyle = "#000";
    ctx.fillText(`🎉 ${winner} MENANG! 🎉`, 300, 200);
  }
}

function draw() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  drawGround();
  drawFinishLine();
  drawPlayer(players.p1);
  drawPlayer(players.p2);
  drawText();
}

function update() {
  if (!gameRunning) return;

  for (let key in players) {
    let p = players[key];
    p.x += p.speed;
    p.distance += p.speed * 0.1;
    p.speed *= 0.98; // gesekan

    if (p.x >= finishLine - 40 && !winner) {
      winner = key === "p1" ? "Player 1" : "Player 2";
      gameRunning = false;
    }
  }

  draw();
  requestAnimationFrame(update);
}

function handleKey(e) {
  if (!gameRunning) return;

  // Player 1: A & D
  if (e.key === "a" && players.p1.lastKey !== "a") {
    players.p1.speed += 1.5;
    players.p1.lastKey = "a";
  } else if (e.key === "d" && players.p1.lastKey !== "d") {
    players.p1.speed += 1.5;
    players.p1.lastKey = "d";
  }

  // Player 2: ArrowLeft & ArrowRight
  if (e.key === "ArrowLeft" && players.p2.lastKey !== "ArrowLeft") {
    players.p2.speed += 1.5;
    players.p2.lastKey = "ArrowLeft";
  } else if (e.key === "ArrowRight" && players.p2.lastKey !== "ArrowRight") {
    players.p2.speed += 1.5;
    players.p2.lastKey = "ArrowRight";
  }
}

startBtn.addEventListener("click", () => {
  players.p1 = { x: 50, y: 280, color: "#d35400", speed: 0, distance: 0, lastKey: null };
  players.p2 = { x: 50, y: 340, color: "#2980b9", speed: 0, distance: 0, lastKey: null };
  winner = null;
  gameRunning = true;
  draw();
  update();
});

document.addEventListener("keydown", handleKey);

draw();
