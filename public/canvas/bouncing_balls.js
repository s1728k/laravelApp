var canvas = document.querySelector('.myCanvas');
var width = canvas.width = window.innerWidth;
var height = canvas.height = window.innerHeight;
var ctx = canvas.getContext('2d');
function Ball(x, y, velX, velY, color, size) {
  this.x = x;
  this.y = y;
  this.velX = velX;
  this.velY = velY;
  this.color = color;
  this.size = size;
}
Ball.prototype.draw = function() {
  ctx.beginPath();
  ctx.fillStyle = this.color;
  ctx.arc(this.x-30, this.y-90, this.size, 0, 2 * Math.PI);
  ctx.fill();
}
Ball.prototype.update = function() {
  if ((this.x + this.size) >= width) {
    this.velX = -(this.velX);
  }

  if ((this.x - this.size) <= 0) {
    this.velX = -(this.velX);
  }

  if ((this.y + this.size) >= height) {
    this.velY = -(this.velY);
  }

  if ((this.y - this.size) <= 0) {
    this.velY = -(this.velY);
  }

  this.x += this.velX;
  this.y += this.velY;
}
Ball.prototype.collisionDetect = function() {
  for (var j = 0; j < balls.length; j++) {
    if (!(this === balls[j])) {
      var dx = this.x - balls[j].x;
      var dy = this.y - balls[j].y;
      var distance = Math.sqrt(dx * dx + dy * dy);

      if (distance < this.size + balls[j].size) {
        balls[j].color = this.color = 'rgb(' + random(0, 255) + ',' + random(0, 255) + ',' + random(0, 255) +')';
      }
    }
  }
}
function degToRad(degrees) {
  return degrees * Math.PI / 180;
};
function random(min, max) {
  var num = Math.floor(Math.random() * (max - min + 1)) + min;
  return num;
}
function loop() {
  ctx.fillStyle = 'rgba(0, 0, 0, 0.25)';
  ctx.fillRect(0, 0, width-30, height-90);

  for(let i = 0; i < balls.length; i++) {
    balls[i].draw();
    balls[i].update();
    balls[i].collisionDetect();
  }

  requestAnimationFrame(loop);
}
				
var balls = [];

while (balls.length < 25) {
  var size = random(10,20);
  var ball = new Ball(
    random(0 + size, width - 30 - size),
    random(0 + size, height - 90 - size),
    random(-1,1),
    random(-7,7),
    'rgb(' + random(0,255) + ',' + random(0,255) + ',' + random(0,255) +')',
    size
  );

  balls.push(ball);
}
loop();