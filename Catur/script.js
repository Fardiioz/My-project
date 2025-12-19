const board = document.getElementById("board");
const turnText = document.getElementById("turn");

let selected = null;
let turn = "white";

const pieces = {
  white: {
    rook: "♖", knight: "♘", bishop: "♗", queen: "♕", king: "♔", pawn: "♙"
  },
  black: {
    rook: "♜", knight: "♞", bishop: "♝", queen: "♛", king: "♚", pawn: "♟"
  }
};

// Representasi papan 8x8
let boardState = [
  ["bR", "bN", "bB", "bQ", "bK", "bB", "bN", "bR"],
  ["bP", "bP", "bP", "bP", "bP", "bP", "bP", "bP"],
  ["", "", "", "", "", "", "", ""],
  ["", "", "", "", "", "", "", ""],
  ["", "", "", "", "", "", "", ""],
  ["", "", "", "", "", "", "", ""],
  ["wP", "wP", "wP", "wP", "wP", "wP", "wP", "wP"],
  ["wR", "wN", "wB", "wQ", "wK", "wB", "wN", "wR"]
];

// Gambar papan awal
function drawBoard() {
  board.innerHTML = "";
  for (let row = 0; row < 8; row++) {
    for (let col = 0; col < 8; col++) {
      const square = document.createElement("div");
      square.classList.add("square");
      square.classList.add((row + col) % 2 === 0 ? "white" : "black");
      square.dataset.row = row;
      square.dataset.col = col;

      const piece = boardState[row][col];
      if (piece) {
        square.textContent = pieceSymbol(piece);
      }

      square.addEventListener("click", onSquareClick);
      board.appendChild(square);
    }
  }
}

function pieceSymbol(code) {
  const color = code[0] === "w" ? "white" : "black";
  const type = code[1];
  switch (type) {
    case "K": return pieces[color].king;
    case "Q": return pieces[color].queen;
    case "R": return pieces[color].rook;
    case "B": return pieces[color].bishop;
    case "N": return pieces[color].knight;
    case "P": return pieces[color].pawn;
  }
}

function onSquareClick(e) {
  const row = parseInt(e.target.dataset.row);
  const col = parseInt(e.target.dataset.col);
  const piece = boardState[row][col];

  if (selected) {
    movePiece(row, col);
  } else if (piece && piece[0] === turn[0]) {
    selected = { row, col };
    e.target.classList.add("selected");
  }
}

function movePiece(targetRow, targetCol) {
  const { row, col } = selected;
  const movingPiece = boardState[row][col];
  const targetPiece = boardState[targetRow][targetCol];

  // Hanya pindahkan jika tidak ke posisi sendiri
  if (!targetPiece || targetPiece[0] !== movingPiece[0]) {
    boardState[targetRow][targetCol] = movingPiece;
    boardState[row][col] = "";
    switchTurn();
  }

  selected = null;
  drawBoard();
}

function switchTurn() {
  turn = turn === "white" ? "black" : "white";
  turnText.textContent = `Giliran: ${turn === "white" ? "Putih" : "Hitam"}`;
}

drawBoard();
