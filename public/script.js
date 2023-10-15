const questions = [
  {
    question: "Quelle est la définition du harcèlement sexuel au travail ?",
    image_path: "./img/image_/image1.png",
    options: [
      "A Toute forme de comportement verbal, non verbal ou physique à connotation sexuelle ayant pour objet ou pour effet de porter atteinte à la dignité d'une personne.",
      "B Un compliment occasionnel sur l'apparence physique d'un collègue.",
    ],
    correctAnswer: 0,
    explanation:
      "Le harcèlement sexuel au travail est défini comme toute forme de comportement à connotation sexuelle portant atteinte à la dignité d'une personne. Selon la loi, il peut inclure des avances non désirées, des commentaires offensants, des demandes sexuelles, ou d'autres comportements similaires.",
  },
  {
    question:
      "Que devrait faire une personne victime de harcèlement sexuel au travail ?",
    image_path: "./img/image_/image5.jpg",
    options: [
      "A Se taire et ignorer les comportements pour éviter des problèmes.",
      "B Signaler le harcèlement à son supérieur hiérarchique, aux ressources humaines ou à toute personne de confiance.",
    ],
    correctAnswer: 1,
    explanation:
      "La personne victime de harcèlement sexuel devrait signaler les faits à son supérieur hiérarchique, aux ressources humaines ou à toute personne de confiance. Il est important de prendre des mesures immédiates pour résoudre la situation et éviter qu'elle ne persiste.",
  },
  {
    question:
      "Quelle est la responsabilité de l'employeur face au harcèlement sexuel au travail ?",
    image_path: "./img/image_/image7.jpg",
    options: [
      "A Ignorer les plaintes des employés pour éviter des complications.",
      "B Prendre des mesures préventives, former le personnel et traiter les plaintes de manière sérieuse et confidentielle.",
    ],
    correctAnswer: 1,
    explanation:
      "L'employeur a la responsabilité de prendre des mesures préventives, de former le personnel et de traiter les plaintes de manière sérieuse et confidentielle. Cela peut inclure la mise en place de politiques anti-harcèlement, des sessions de sensibilisation et la création d'un environnement où les employés se sentent en sécurité pour signaler tout incident.",
  },
  {
    question:
      "Martine demande à son collègue Jules d'appuyer sur le bouton de l'ascenseur dont il bloque le passage afin de se rendre au troisième étage." +
      "Jules lui répond: \"Tu sais que pour toi je ferais tout\". C'est la troisième fois aujourd'hui que Jules lui fait cette remarque (la première pour une agrafeuse, la seconde pour un dossier)" +
      " A présent Martine est gênée lorsqu'elle doit s'adresser à Jules. Mais elle se dit qu'il ne fait rien de mal au fond et que c'est plutôt elle qui devrait se détendre un peu." +
      "Martine a-t-elle raison ?",
    image_path: "./img/image_/image77.jpg",
    options: [
      "A Oui. Les enjeux du monde professionnel exercent parfois une forme de pression sur les employés. Il est normal que ceux-ci adoptent un comportement un peu plus léger parfois, comme Jules, sans que cela soit condamnable pour autant.",
      "B Non. Elle a tort. Martine devrait en référer à son responsable et demander à Jules de cesser ses remarques.",
    ],
    correctAnswer: 1,
    explanation:
      "Ce que fait Jules s'appelle du harcèlement sexuel et est condamnable par la loi." +
      "Jules devrait cesser sur le champs ce genre d'agissement même si son intention est de complimenter Martine ou de faire de l'humour." +
      "Martine pourrait en référer à son responsable et Jules devrait-être sanctionné.",
  },
];

let currentQuestionIndex = 0;
let score = 0;

function initializeFirstQuestion() {
  const questionContainer = document.querySelector(".slide-container");
  questionContainer.querySelector("h1").innerText = `Question 1`;

  initializeQuestion();
}

async function getJsonSituation(id_situation) {
  const response = await fetch(
    "http://localhost:8000/fr/quizz/getJsonSituation?id_situation=" +
      id_situation,
  );
  return response.json();
}

function initializeQuestion() {
  const questionContainer = document.querySelector(".slide-container");
  const optionsContainer = document.querySelector(".options");

  document.getElementById("question-title").innerText = `Question ${
    currentQuestionIndex + 1
  }`;

  getJsonSituation(currentQuestionIndex + 1).then((data) => {
    document.getElementById("question-text").innerText =
      data.situation.question;

    document.getElementById("main_image").src =
      "http://localhost:8000/uploads/images/" + data.situation.imageName;

    const options = data.situation.answers;
    optionsContainer.innerHTML = "";

    options.forEach((option, index) => {
      const button = document.createElement("button");
      button.innerText = option.content;
      button.onclick = function () {
        selectAnswer(index, data);
      };

      optionsContainer.appendChild(button);
    });
  });
}

document.addEventListener("DOMContentLoaded", function () {
  initializeFirstQuestion();
});

function selectAnswer(index, data) {
  const selectedOption = document.querySelector(".options .selected");
  if (selectedOption) {
    selectedOption.classList.remove("selected");
  }

  document.querySelectorAll(".options button")[index].classList.add("selected");

  checkAnswer(data);
}

function checkAnswer(data) {
  const selectedOption = document.querySelector(".options .selected");
  if (selectedOption) {
    const selectedIndex = Array.from(
      selectedOption.parentNode.children,
    ).indexOf(selectedOption);

    if (selectedIndex === data.situation.correctAnswer) {
      score += 10;
      document.getElementById("result").innerText = "Bonne réponse! +10 points";
    } else {
      document.getElementById("result").innerText =
        "Mauvaise réponse. Aucun point.";
    }

    document.getElementById("explanation").innerText =
      data.situation.explanation;

    document
      .querySelectorAll(".options button")
      .forEach((option) => (option.disabled = true));

    // document.getElementById('nextButton').style.display = 'block';
    document.getElementById("result-container").style.display = "block";
  }
}

function nextQuestion() {
  document.getElementById("result").innerText = "";
  document.getElementById("explanation").innerText = "";
  document.querySelectorAll(".options button").forEach((option) => {
    option.classList.remove("selected");
    option.disabled = false;
  });
  // document.getElementById('nextButton').style.display = 'none';
  document.getElementById("result-container").style.display = "none";

  currentQuestionIndex++;

  if (currentQuestionIndex < questions.length) {
    initializeQuestion();
  } else {
    const finalContainer = document.querySelector(".slide-container");
    finalContainer.innerHTML = `<h1>Score final: ${score}</h1>`;

    let text = "";
    if (score >= 30) {
      text =
        "Félicitations! Vous avez démontré une compréhension approfondie des problématiques de harcèlement sexuel au travail. Votre sensibilité à ce sujet est précieuse pour créer des environnements de travail sûrs et respectueux.";
    } else if (score >= 20) {
      text =
        "Bien joué! Votre connaissance des problématiques de harcèlement sexuel au travail est solide. Continuez à approfondir vos compétences pour contribuer à des espaces de travail sains.";
    } else {
      text =
        "Il semble y avoir des aspects des problématiques de harcèlement sexuel au travail qui nécessitent davantage d'attention. Recherchez des ressources supplémentaires pour renforcer vos connaissances et contribuer à la prévention.";
    }

    text += "<br><br>";
    text +=
      "Le harcèlement sexuel au travail est un problème sérieux qui impacte la vie professionnelle et personnelle de nombreuses personnes. En comprenant ces enjeux, vous jouez un rôle essentiel dans la création d'un environnement de travail respectueux et équitable.";

    text += "<br/><br/>Le harcèlement sexuel peut être puni pénalement :";

    text +=
      "<ul><li>Deux ans d’emprisonnement et <strong>30 000 euros</strong> d’amende </li>";
    text +=
      "<li>Trois ans d’emprisonnement et <strong>45 000 euros</strong> d’amende lorsque les faits sont commis par une personne qui abuse de l’autorité que lui confèrent ses fonctions.</li></ul>";

    text +=
      '<a class="lienblanc" href="https://questionsexualite.fr/lutter-contre-les-violences-et-discriminations/les-violences-sexistes-et-sexuelles/qu-est-ce-que-le-harcelement-sexuel-et-quelles-sont-les-sanctions" />Harcèlement sexuel et sanctions, que faut-il savoir ?</a>';
    finalContainer.innerHTML += `<div>${text}</div>`;
    finalContainer.innerHTML += `<div><a href="./form-certif.html" class="nextButton lastbutton">Suivant</a></div>`;
  }
}
