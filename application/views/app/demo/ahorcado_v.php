<style>

/* Mixin's */ 



/*body {
  background:#c1d72e;
  font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 
  color:white;
  height:100%;
  text-align:center;
  font-size:18px;
}*/

.wrappper{
  /*@include clear;*/
  width:100%;
  margin:0 auto;
}


canvas{
  color:white;
  border: white dashed 2px;
  padding:15px;
}

/*h1, h2, h3 {
	font-family: 'Roboto', sans-serif;
	font-weight: 100;
	text-transform: uppercase;
   margin:5px 0;
}

h1 {
	font-size: 2.6em;
}

h2 {
	font-size: 1.6em;
}*/

p{
  font-size: 1.6em;
}

#alphabet {
  margin:15px auto;
  padding:0;
  max-width:900px;
}

#alphabet li {
  float:left;
  margin: 0 10px 10px 0;
  list-style:none;
  width:35px;
  height:30px;
  padding-top:5px;
  padding-bottom:5px;
  background-color: #f5f5f5;
  color: #333;
  cursor:pointer;
  border: solid 1px white;
  text-align: center;
}

#my-word {
  margin: 0;
  display: block;
  padding: 0;
  display:block;
}

#my-word li {
  position: relative;
  list-style: none;
  margin: 0;
  display: inline-block;
  padding: 0 10px;
  font-size:1.6em;
}

.active {
  /*@include opacity;*/
  cursor:default;
    
  /*&:hover{
      @include fade;
      @include opacity;
  }*/
}

#mylives{
  font-size:1.6em;
  text-align:center;
  display:block;
}

button{
  background:#c1d72e;
  color:white;
  border: solid 1px white;
  text-decoration:none;
  cursor:pointer;
  font-size:1.2em;
  padding:18px 10px;
  width:180px;
  margin: 10px;
  outline: none;
  
    /*&:hover{
      @include transition;
      background: white;
      border: solid 1px white;
      color:#c1d72e;
    }*/
}

    @media (max-width: 767px) {
        #alphabet { padding:0 0 0 15px;}
    }

  @media (max-width: 480px) {
    #alphabet { padding:0 0 0 25px; }
}
</style>

<script>
window.onload = function () {

  var alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'Q', 'L', 'M', 'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

  var categories;         // Array of topics
  var chosenCategory;     // Selected catagory
  var getHint;           // Word getHint
  var word;              // Selected word
  var guess;             // Geuss
  var geusses = [];      // Stored geusses
  var lives;             // Lives
  var counter;           // Count correct geusses
  var space;              // Number of spaces in word '-'

  /*Get elements*/
  var showLives = document.getElementById("mylives");
  var showCatagory = document.getElementById("scatagory");
  var getHint = document.getElementById("hint");
  var showClue = document.getElementById("clue");



  // create alphabet ul
  var buttons = function () {
    myButtons = document.getElementById('buttons');
    letters = document.createElement('ul');

    for (var i = 0; i < alphabet.length; i++)
    {
      letters.id = 'alphabet';
      list = document.createElement('li');
      list.id = 'letter';
      list.innerHTML = alphabet[i];
      check();
      myButtons.appendChild(letters);
      letters.appendChild(list);
    }
  }
    

  // Select Catagory
  var selectCat = function ()
  {
    if (chosenCategory === categories[0]) {
      catagoryName.innerHTML = "La categoría seleccionada es Premier League Football Teams";
    } else if (chosenCategory === categories[1]) {
      catagoryName.innerHTML = "La categoría seleccionada es Huesos del Cuerpo Humano";
    } else if (chosenCategory === categories[2]) {
      catagoryName.innerHTML = "La categoría seleccionada es Ciudades";
    }
  }

  // Create geusses ul
  result = function () {
    wordHolder = document.getElementById('hold');
    correct = document.createElement('ul');

    for (var i = 0; i < word.length; i++)
    {
      correct.setAttribute('id', 'my-word');
      guess = document.createElement('li');
      guess.setAttribute('class', 'guess');
      if (word[i] === "-") {
        guess.innerHTML = "-";
        space = 1;
      } else {
        guess.innerHTML = "_";
      }

      geusses.push(guess);
      wordHolder.appendChild(correct);
      correct.appendChild(guess);
    }
  }

  // Show lives
  comments = function () {
    showLives.innerHTML = "Te quedan " + lives + " vidas";
    if (lives < 1) {
      showLives.innerHTML = "Perdiste";
    }
    for (var i = 0; i < geusses.length; i++) {
      if (counter + space === geusses.length) {
        showLives.innerHTML = "¡Ganaste!";
      }
    }
  }

  /* Animate man */
  var animate = function () {
    var drawMe = lives ;
    drawArray[drawMe]();
  }


  /*Hangman*/
  canvas =  function()
  {
    myStickman = document.getElementById("stickman");
    context = myStickman.getContext('2d');
    context.beginPath();
    context.strokeStyle = "#fff";
    context.lineWidth = 2;
  };

  head = function()
  {
    myStickman = document.getElementById("stickman");
    context = myStickman.getContext('2d');
    context.beginPath();
    context.arc(60, 25, 10, 0, Math.PI*2, true);
    context.stroke();
  }
    
  draw = function($pathFromx, $pathFromy, $pathTox, $pathToy)
  {
    context.moveTo($pathFromx, $pathFromy);
    context.lineTo($pathTox, $pathToy);
    context.stroke(); 
  }

  frame1 = function() {
    draw (0, 150, 150, 150);
  };
  
  frame2 = function() {
    draw (10, 0, 10, 600);
  };

  frame3 = function() {
    draw (0, 5, 70, 5);
  };

  frame4 = function() {
    draw (60, 5, 60, 15);
  };

  torso = function() {
    draw (60, 36, 60, 70);
  };

  rightArm = function() {
    draw (60, 46, 100, 50);
  };

  leftArm = function() {
    draw (60, 46, 20, 50);
  };

  rightLeg = function() {
    draw (60, 70, 100, 100);
  };

  leftLeg = function() {
    draw (60, 70, 20, 100);
  };

  drawArray = [rightLeg, leftLeg, rightArm, leftArm,  torso,  head, frame4, frame3, frame2, frame1]; 


  /** OnClick Function */
  check = function () 
  {
    list.onclick = function () {
      var geuss = (this.innerHTML);
      this.setAttribute("class", "active");
      this.onclick = null;
      for (var i = 0; i < word.length; i++)
      {
        if (word[i] === geuss)
        {
          geusses[i].innerHTML = geuss;
          counter += 1;
        } 
      }
      var j = (word.indexOf(geuss));
      if (j === -1) {
        lives -= 1;
        comments();
        animate();
      } else {
        comments();
      }
    }
  }

    
  /**
   * Play game
   */
  play = function ()
  {
    categories = [
        ["everton", "liverpool", "swansea", "chelsea", "hull", "manchester-city", "newcastle-united"],
        ["alien", "dirty-harry", "gladiator", "finding-nemo", "jaws"],
        ["MEDELLIN", "BOGOTA", "SANTA MARTA", "VALLEDUPAR", "POPAYAN"]
    ];

    chosenCategory = categories[Math.floor(Math.random() * categories.length)];
    word = chosenCategory[Math.floor(Math.random() * chosenCategory.length)];
    word = word.replace(/\s/g, "-");
    console.log(word);
    buttons();

    geusses = [ ];
    lives = 10;
    counter = 0;
    space = 0;
    result();
    comments();
    selectCat();
    canvas();
  }

  play();

  /**
   * Mostrar pista
   */
  hint.onclick = function()
  {
    hints = [
      ["Based in Mersyside", "Based in Mersyside", "First Welsh team to reach the Premier Leauge", "Owned by A russian Billionaire", "Once managed by Phil Brown", "2013 FA Cup runners up", "Gazza's first club"],
      ["Science-Fiction horror film", "1971 American action film", "Historical drama", "Anamated Fish", "Giant great white shark"],
      ["Única ciudad con metro en Colombia", "Capital de Colombia", "En esta cuidad murió Simón Bolívar", "Netherlands capital", "Czech Republic capital"]
    ];

    var catagoryIndex = categories.indexOf(chosenCategory);
    var hintIndex = chosenCategory.indexOf(word);
    showClue.innerHTML = "Pista: - " +  hints [catagoryIndex][hintIndex];
  };

  /* Reset */
  document.getElementById('reset').onclick = function() 
    {
    correct.parentNode.removeChild(correct);
    letters.parentNode.removeChild(letters);
    showClue.innerHTML = "";
    context.clearRect(0, 0, 400, 400);
    play();
  }
}
</script>


<div class="card" style="max-width: 850px; margin: 0px auto;">
  <div class="card-body">
    <div class="alert alert-info">
        <i class="fa fa-info-circle"></i>
        Use el alfabeto que esta abajo para escribir una palabra
    </div>

    <div class="row">
      <div class="col-md-6">
        <div id="buttons"></div>
      </div>
      <div class="col-md-6">
        <canvas id="stickman" style="background-color: #0d47a1;">
          This Text will show if the Browser does NOT support HTML5 Canvas tag
        </canvas>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <p id="catagoryName"></p>
        <div id="hold"></div>
        <div class="alert alert-info" id="clue"></div>
      </div>
      <div class="col-md-6">
        <p id="mylives"></p>
      </div>
    </div>
    
    <div class="">
        <button id="hint" class="btn btn-secondary">Pista</button>
        <button id="reset" class="btn btn-success">Jugar de nuevo</button>
    </div>
  </div>
</div>