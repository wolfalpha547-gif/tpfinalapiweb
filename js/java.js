const canvas = document.getElementById('game');
const context = canvas.getContext('2d');
const aspectos = window.aspectosJuego || {};

const imagenesAspectos = {
  jugador: new Image(),
  pelota: new Image(),
  IA: new Image()
};

Object.entries(imagenesAspectos).forEach(([tipo, imagen]) => {
  const nombreSkin = aspectos[tipo];
  if (nombreSkin) {
    imagen.src = `../imagenes/skins/${nombreSkin}.png`;
  }
});

function dibujarAspecto(imagen, x, y, width, height, color) {
  if (imagen.complete && imagen.naturalWidth > 0) {
    context.drawImage(imagen, x, y, width, height);
  } else {
    context.fillStyle = color;
    context.fillRect(x, y, width, height);
  }
}

const grid = 15;
const paddleHeight = grid * 5; // 80
const maxPaddleY = canvas.height - grid - paddleHeight;

var paddleSpeed = 6;
var ballSpeed = 5;
var iaSpeed = 4.5;
var puntosJugador = 0;
var puntosIA = 0;
var juegoTerminado = false;
var rankingGuardado = false;

const leftPaddle = {
  // start in the middle of the game on the left side
  x: grid * 2,
  y: canvas.height / 2 - paddleHeight / 2,
  width: grid,
  height: paddleHeight,

  // paddle velocity
  dy: 0
};
const rightPaddle = {
  // start in the middle of the game on the right side
  x: canvas.width - grid * 3,
  y: canvas.height / 2 - paddleHeight / 2,
  width: grid,
  height: paddleHeight,

  // paddle velocity
  dy: 0
};
const ball = {
  // start in the middle of the game
  x: canvas.width / 2,
  y: canvas.height / 2,
  width: grid,
  height: grid,

  // keep track of when need to reset the ball position
  resetting: false,

  // ball velocity (start going to the top-right corner)
  dx: ballSpeed,
  dy: -ballSpeed
};

function collides(obj1, obj2) {
  return obj1.x < obj2.x + obj2.width &&
         obj1.x + obj1.width > obj2.x &&
         obj1.y < obj2.y + obj2.height &&
         obj1.y + obj1.height > obj2.y;
}

function moverIA() {
  const centroIA = rightPaddle.y + rightPaddle.height / 2;
  const centroPelota = ball.y + ball.height / 2;
  const distancia = centroPelota - centroIA;
  const zonaMuerta = 8;

  if (Math.abs(distancia) <= zonaMuerta) {
    rightPaddle.dy = 0;
  } else if (distancia < 0) {
    rightPaddle.dy = -Math.min(iaSpeed, Math.abs(distancia));
  } else {
    rightPaddle.dy = Math.min(iaSpeed, Math.abs(distancia));
  }
}

const volver = document.getElementById("fin");
volver.style.display = "none";

function terminarJuego() {
  juegoTerminado = true;
  leftPaddle.dy = 0;
  rightPaddle.dy = 0;

  if (rankingGuardado) {
    return;
  }
  rankingGuardado = true;

  fetch('guardar_ranking.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      puntuacion: puntosJugador
    })
  }).catch(error => {
    console.error('No se pudo guardar el resultado:', error);
  });

  volver.style.display = "";
}

function dibujarMarcador() {
  context.fillStyle = 'white';
  context.font = '36px Arial';
  context.textAlign = 'center';
  context.fillText(`${puntosJugador}  -  ${puntosIA}`, canvas.width / 2, 55);

  if (juegoTerminado) {
    context.font = '48px Arial';
    const mensaje = puntosJugador >= 11 ? 'Ganaste' : 'Perdiste';
    context.fillText(mensaje, canvas.width / 2, canvas.height / 2);
  }
}

// game loop
function loop() {
  requestAnimationFrame(loop);
  context.clearRect(0,0,canvas.width,canvas.height);

  if (juegoTerminado) {
    dibujarAspecto(imagenesAspectos.jugador, leftPaddle.x, leftPaddle.y, leftPaddle.width, leftPaddle.height, 'white');
    dibujarAspecto(imagenesAspectos.IA, rightPaddle.x, rightPaddle.y, rightPaddle.width, rightPaddle.height, 'white');
    dibujarAspecto(imagenesAspectos.pelota, ball.x, ball.y, ball.width, ball.height, 'white');
    dibujarMarcador();
    return;
  }

  moverIA();

  // move paddles by their velocity
  leftPaddle.y += leftPaddle.dy;
  rightPaddle.y += rightPaddle.dy;

  // prevent paddles from going through walls
  if (leftPaddle.y < grid) {
    leftPaddle.y = grid;
  }
  else if (leftPaddle.y > maxPaddleY) {
    leftPaddle.y = maxPaddleY;
  }

  if (rightPaddle.y < grid) {
    rightPaddle.y = grid;
  }
  else if (rightPaddle.y > maxPaddleY) {
    rightPaddle.y = maxPaddleY;
  }

  // draw paddles
  dibujarAspecto(imagenesAspectos.jugador, leftPaddle.x, leftPaddle.y, leftPaddle.width, leftPaddle.height, 'white');
  dibujarAspecto(imagenesAspectos.IA, rightPaddle.x, rightPaddle.y, rightPaddle.width, rightPaddle.height, 'white');

  // move ball by its velocity
  ball.x += ball.dx;
  ball.y += ball.dy;

  // prevent ball from going through walls by changing its velocity
  if (ball.y < grid) {
    ball.y = grid;
    ball.dy *= -1;
  }
  else if (ball.y + grid > canvas.height - grid) {
    ball.y = canvas.height - grid * 2;
    ball.dy *= -1;
  }

  // reset ball if it goes past paddle (but only if we haven't already done so)
  if ( (ball.x < 0 || ball.x > canvas.width) && !ball.resetting) {
    ball.resetting = true;

    if (ball.x < 0) {
      puntosIA++;
    } else {
      puntosJugador++;
    }

    if (puntosJugador >= 11 || puntosIA >= 11) {
      terminarJuego();
      ball.resetting = false;
    }

    // give some time for the player to recover before launching the ball again
    if (!juegoTerminado) {
      setTimeout(() => {
        ball.resetting = false;
        ball.x = canvas.width / 2;
        ball.y = canvas.height / 2;
        ball.dx = Math.random() < 0.5 ? -ballSpeed : ballSpeed;
        ball.dy = -ballSpeed;
      }, 400);
    }
  }

  // check to see if ball collides with paddle. if they do change x velocity
  if (collides(ball, leftPaddle)) {
    ball.dx *= -1;

    // move ball next to the paddle otherwise the collision will happen again
    // in the next frame
    ball.x = leftPaddle.x + leftPaddle.width;
  }
  else if (collides(ball, rightPaddle)) {
    ball.dx *= -1;

    // move ball next to the paddle otherwise the collision will happen again
    // in the next frame
    ball.x = rightPaddle.x - ball.width;
  }

  // draw ball
  dibujarAspecto(imagenesAspectos.pelota, ball.x, ball.y, ball.width, ball.height, 'white');

  // draw walls
  context.fillStyle = 'lightgrey';
  context.fillRect(0, 0, canvas.width, grid);
  context.fillRect(0, canvas.height - grid, canvas.width, canvas.height);

  // draw dotted line down the middle
  for (let i = grid; i < canvas.height - grid; i += grid * 2) {
    context.fillRect(canvas.width / 2 - grid / 2, i, grid, grid);
  }

  dibujarMarcador();
}

// listen to keyboard events to move the paddles
document.addEventListener('keydown', function(e) {
  // w key
  if (e.which === 87) {
    leftPaddle.dy = -paddleSpeed;
  }
  // a key
  else if (e.which === 83) {
    leftPaddle.dy = paddleSpeed;
  }
});


document.addEventListener('keyup', function(e) {
  if (e.which === 83 || e.which === 87) {
    leftPaddle.dy = 0;
  }
});

requestAnimationFrame(loop);