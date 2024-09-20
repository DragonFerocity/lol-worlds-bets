document.body.style.background = "linear-gradient(to right, rgba(25, 30, 36, 0.2) 0%, rgba(25, 30, 36, 0.2) 100%), url('../background.jpg')";


let xmlHttp;
function srvTime(){
    try {
        //FF, Opera, Safari, Chrome
        xmlHttp = new XMLHttpRequest();
    }
    catch (err1) {
        //IE
        try {
            xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
        }
        catch (err2) {
            try {
                xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
            }
            catch (eerr3) {
                //AJAX not supported, use CPU time.
                alert("AJAX not supported");
            }
        }
    }
    xmlHttp.open('HEAD',window.location.href.toString(),false);
    xmlHttp.setRequestHeader("Content-Type", "text/html");
    xmlHttp.send('');
    return xmlHttp.getResponseHeader("Date");
}


let today = new Date();
let serverTime = new Date(srvTime().toString()); //The server is in GMT (Greenwhich Mean Time), so bets close on the 10th at 7am (2AM CST)
let betsStart = new Date("9/19/2024 00:00 AM PST");
let betsEnd = new Date("9/25/2024 05:00 AM PST");
console.log(srvTime(), serverTime);
let TOURNAMENT_ID = 7;

if (serverTime > betsEnd) {
  document.getElementById("worlds-bets-container").outerHTML = "";
  document.getElementById("worlds-bets-closed").style.display = "block";
} else if (serverTime < betsStart) {
  document.getElementById("worlds-bets-container").outerHTML = "";
  document.getElementById("worlds-bets-not-open").style.display = "block";
} else {
  ///// When bets are opened, code goes here!
  

  setCountdown();
  setInterval(setCountdown, 1000);

  function setCountdown() {
    serverTime = new Date(srvTime().toString()); //The server is in GMT (Greenwhich Mean Time), so bets close on the 10th at 7am (2AM CST)
    let dateDiff = betsEnd.getTime() - serverTime.getTime();
    let timezoneOffset = serverTime.getTimezoneOffset() * 60 * 1000; //Minutes, subtract this from everything below
    dateDiff -= timezoneOffset;
    let daysTill = Math.floor(dateDiff / (1000 * 60 * 60 * 24));
    let hoursTill = Math.floor(dateDiff / (1000 * 60 * 60)) - (daysTill * 24);
    let minutesTill = Math.floor(dateDiff / (1000 * 60)) - (hoursTill * 60) - (daysTill * 60 * 24);
    let secondsTill = Math.floor(dateDiff / 1000) - (minutesTill * 60) - (hoursTill * 60 * 60) - (daysTill * 60 * 60 * 24);

    document.getElementById("countdown").innerText = "Bets close in " + daysTill + " days, " + hoursTill + " hours, " + minutesTill + " minutes, and " + secondsTill + " seconds.";

    if (serverTime > betsEnd) {
      closeBets();
    }
  }

  let champSelectContainers = document.querySelectorAll(".champion-select");
  let championSelect = document.getElementById("champions-select");
  if (champSelectContainers) {
    champSelectContainers.forEach(container => {
      container.appendChild(championSelect.content.cloneNode(true));
    });
  }

  let dragonSelectContainers = document.querySelectorAll(".dragon-types");
  let dragonTypeSelect = document.getElementById("dragon-types");
  if (dragonSelectContainers) {
    dragonSelectContainers.forEach(container => {
      container.appendChild(dragonTypeSelect.content.cloneNode(true));
    });
  }

  function lastStep(step) {
    switch (step) {
      case 1:
        let currentStep = sessionStorage.getItem("step");

        if (currentStep === "2") resizeContentBox(document.getElementById("bets-step-2"), document.getElementById("bets-step-1"));
        if (currentStep === "3") resizeContentBox(document.getElementById("bets-step-3"), document.getElementById("bets-step-1"));
        if (currentStep === "4") resizeContentBox(document.getElementById("bets-step-4"), document.getElementById("bets-step-1"));
        if (currentStep === "5") resizeContentBox(document.getElementById("bets-step-5"), document.getElementById("bets-step-1"));
        hideError("bets-data-error", false);
        hideError("bets-data-error-unfilled", false);
        hideError("bets-update-user-pass-error", false);
        hideError("bets-create-user-pass-error", false);
        break;
    }
  }
  function nextStep(step) {
    switch (step) {
      case 2:
        resizeContentBox(document.getElementById("bets-step-1"), document.getElementById("bets-step-2"));
        sessionStorage.setItem("mode", "add");
        sessionStorage.setItem("step", "2");
        break;
      case 3:
        resizeContentBox(document.getElementById("bets-step-1"), document.getElementById("bets-step-3"));
        sessionStorage.setItem("mode", "update");
        sessionStorage.setItem("step", "3");
        break;
      case 4:
        let mode = sessionStorage.getItem("mode");

        if (mode === "add") resizeContentBox(document.getElementById("bets-step-2"), document.getElementById("bets-step-4"));
        if (mode === "update") resizeContentBox(document.getElementById("bets-step-3"), document.getElementById("bets-step-4"));
        sessionStorage.setItem("step", "4");
        break;
      case 5:
        resizeContentBox(document.getElementById("bets-step-4"), document.getElementById("bets-step-5"));
        document.getElementById("bets-step-4").style.display = "none";
        document.getElementById("bets-step-5").style.display = "block";
        sessionStorage.setItem("step", "5");
        break;
    }
  }

  function checkLogin() {
    const display = document.getElementById("bets-displayname-create");

    if (!display.value) {
      showError("bets-create-user-pass-error", "", true, "bets-step-2");
    } else {
      showLoadingSpinner();
      checkdbLoginExists(display.value).then((exists) => {
        if (exists == "yes") {
          showError("bets-user-pass-exists-error", "", true, "bets-step-2");
          hideError("bets-create-user-pass-error", true, "bets-step-2");
        } else {
          hideError("bets-user-pass-exists-error", false);
          hideError("bets-create-user-pass-error", false);
          setSliders();
          nextStep(4);
        }
        hideLoadingSpinner();
      });
    }
  }

  function getBets() {
    showLoadingSpinner();
    let displayName = encodeURIComponent(document.getElementById("bets-displayname-update").value);
    let password = encodeURIComponent(document.getElementById("bets-password-update").value);
    
    let promise = new Promise((resolve) => {
      getBetData(displayName, password).then(betData => {
        if (!betData || betData == "no") {
          //Check if there is an entry without a password
          getBetData(displayName, "").then(betData => {
            resolve(betData);
          });
        } else {
          resolve(betData);
        }
      });
    });

    promise.then(betData => {
      if (!betData || betData == "no") {
        showError("bets-update-user-pass-error", "", true, "bets-step-3");
      } else {
        let bets = decodeURIComponent(String(betData)).split("|");
        let bets1 = bets[0].split(",");
        let bets2 = bets[1].split(",");
        let bets3 = bets[2];
        document.getElementById("bet-1-a").firstElementChild.value = bets1[0].replace("+", " ") || "";
        document.getElementById("bet-1-b").firstElementChild.value = bets1[1].replace("+", " ") || "";
        document.getElementById("bet-1-c").firstElementChild.value = bets1[2].replace("+", " ") || "";
        document.getElementById("bet-2-a").firstElementChild.value = bets2[0].replace("+", " ") || "";
        document.getElementById("bet-2-b").firstElementChild.value = bets2[1].replace("+", " ") || "";
        document.getElementById("bet-2-c").firstElementChild.value = bets2[2].replace("+", " ") || "";
        document.getElementById("bet-3").value = bets3.replace("+", " ");
        nextStep(4);
      }
      setSliders();
      hideLoadingSpinner();
    })

  }

  function updateBets() {
    try {
      showLoadingSpinner();
      let displayName = "";
      let password = "";

      if (sessionStorage.getItem("mode") == "update") {
        displayName = encodeURIComponent(document.getElementById("bets-displayname-update").value);
        password = encodeURIComponent(document.getElementById("bets-password-update").value);
      } else {
        displayName = encodeURIComponent(document.getElementById("bets-displayname-create").value);
        password = encodeURIComponent(document.getElementById("bets-password-create").value);
      }

      let bet1a = encodeURIComponent(document.getElementById("bet-1-a").firstElementChild.value);
      let bet1b = encodeURIComponent(document.getElementById("bet-1-b").firstElementChild.value);
      let bet1c = encodeURIComponent(document.getElementById("bet-1-c").firstElementChild.value);
      let bet1 = bet1a + "," + bet1b + "," + bet1c;
      let bet2a = encodeURIComponent(document.getElementById("bet-2-a").firstElementChild.value);
      let bet2b = encodeURIComponent(document.getElementById("bet-2-b").firstElementChild.value);
      let bet2c = encodeURIComponent(document.getElementById("bet-2-c").firstElementChild.value);
      let bet2 = bet2a + "," + bet2b + "," + bet2c;
      let bet3 = encodeURIComponent(document.getElementById("bet-3").value);
      let bet4 = encodeURIComponent("/");
      let bet5 = encodeURIComponent("/");
      
      if (!bet1a || !bet1b || !bet1c || !bet2a || !bet2b || !bet2c || !bet3 || !bet4 || !bet5) {
        displayError("bets-data-error-unfilled", "", true, "bets-step-4");
      } else if (bet1a == bet1b || bet1a == bet1c || bet1b == bet1c || bet2a == bet2b || bet2a == bet2c || bet2b == bet2c) {
        displayError("bets-data-error-duplicate", "", true, "bets-step-4");
      } else {
        updateBetData(displayName, password, sessionStorage.getItem("mode"), bet1, bet2, bet3, bet4, bet5).then(betData => {
          if (betData == "added" || betData == "updated") {
            console.log(betData);
            nextStep(5);
            document.getElementById("bets-displayname-update").value = "";
            document.getElementById("bets-password-update").value = "";
            document.getElementById("bets-displayname-create").value = "";
            document.getElementById("bets-password-create").value = "";
            document.getElementById("bet-1-a").firstElementChild.selectedIndex = 0;
            document.getElementById("bet-1-b").firstElementChild.selectedIndex = 0;
            document.getElementById("bet-1-c").firstElementChild.selectedIndex = 0;
            document.getElementById("bet-2-a").firstElementChild.selectedIndex = 0;
            document.getElementById("bet-2-b").firstElementChild.selectedIndex = 0;
            document.getElementById("bet-2-c").firstElementChild.selectedIndex = 0;
            document.getElementById("bet-3").value = "";
            hideError("bets-data-error", false);
            hideError("bets-data-error-unfilled", false);
            hideError("bets-data-error-duplicate", false);
            hideError("bets-data-error-unknown", false);
            hideError("bets-update-user-pass-error", false);
            hideError("bets-create-user-pass-error", false);
          } else {
            displayError("bets-data-error", "", true, "bets-step-4");
          }
          hideLoadingSpinner();
        });
      }
    } catch (error) {
      displayError("bets-data-error-unknown", "An unspecified error has occurred: <i>" + error.toString() + "</i>", true, "bets-step-4");
    }
  }

  function displayError(errorElementID, text = "", resizeBox = false, stageElementID = "") {
    let element = document.getElementById(errorElementID);

    element.classList.remove("hidden");

    if (text) {
      document.getElementById(element).innerHTML = text;
    }
    hideLoadingSpinner();
    document.body.scrollTop = 0;

    if (resizeBox) {
      resizeContentBox(document.getElementById(stageElementID), document.getElementById(stageElementID));
    }
  }
  function hideError(errorElementID, resizeBox = false, stageElementID = "") {
    let element = document.getElementById(errorElementID);

    element.classList.add("hidden");

    hideLoadingSpinner();

    if (resizeBox) {
      resizeContentBox(document.getElementById(stageElementID), document.getElementById(stageElementID));
    }
  }

  async function getResponseJSON(responseObject) {
    let text = await responseObject.text();
    return text;
  }
  async function getBetData(username, password) {
    let response = await fetch("./api/getBetData.php?user=" + username + "&pass=" + password + "&tournament=" + TOURNAMENT_ID);
    return getResponseJSON(response);
  }
  async function updateBetData(username, password, mode, bet1, bet2, bet3, bet4, bet5) {
    let response = await fetch("./api/updateBetData.php?user=" + username + "&pass=" + password + "&mode=" + mode + "&tournament=" + TOURNAMENT_ID + "&bet1=" + bet1 + "&bet2=" + bet2 + "&bet3=" + bet3 + "&bet4=" + bet4 + "&bet5=" + bet5);
    return getResponseJSON(response);
  }
  async function checkdbLoginExists(username) {
    let response = await fetch("./api/checkLogin.php?user=" + username + "&tournament=" + TOURNAMENT_ID);
    return getResponseJSON(response);
  }

  ////////////////////////
  // Animations
  function resizeContentBox(currentStage, nextStage) {
    const box = document.getElementById("worlds-bets-steps");
    let interval;
    const boxHeight = parseInt(box.offsetHeight);
    let currentStageHeight = parseInt(currentStage.offsetHeight) + 20;
    let nextStageHeight = getElementHeight(nextStage);
    const heightDiff = nextStageHeight - currentStageHeight;
    const animationLength = 250; //ms
    const frameLength = 10; //ms
    const maxFrames = Math.round(animationLength / frameLength);
    const heightChangePerFrame = heightDiff / maxFrames;
    let frameCount = 0;
    clearInterval(interval);

    interval = setInterval(frame, frameLength);
    function frame() {
      if (frameCount > maxFrames) {
        clearInterval(interval);
        currentStage.style.display = "none";
        nextStage.style.display = "block";
        box.style.height = nextStageHeight;
        currentStage.style.opacity = 1;
        nextStage.style.opacity = 1;
      } else {
        const opacity = frameCount / maxFrames;
        const animFunc = (opacity * opacity) / (2 * (opacity * opacity - opacity) + 1);
        box.style.height = currentStageHeight + (heightDiff * animFunc)//(currentStageHeight + (heightChangePerFrame * frameCount)) + "px";
        currentStage.style.opacity = 1 - animFunc;
        frameCount++;
      }
    }
  }

  function getElementHeight(element) {
    let clone = element.cloneNode(true);
    const box = document.getElementById("worlds-bets-steps-hidden");
    clone.style.cssText = "position: fixed; left: 0; top: 0; visibility: hidden; height: unset; max-height: unset; overflow: hidden;";

    box.append(clone);
    let height = clone.offsetHeight;
    clone.remove();
    return parseInt(height);
  }

  function setSliders() {
    let sliders = document.getElementsByClassName("slider");
    for (let i = 0; i < sliders.length; i++) {
      let slider = sliders[i];
      let sliderValue = slider.nextElementSibling;

      sliderValue.innerHTML = slider.value;
      slider.oninput = function() {
        sliderValue.innerHTML = this.value;
      }
    }
  }

  function closeBets() {
    document.getElementById("worlds-bets-container").outerHTML = "";
    document.getElementById("worlds-bets-closed").style.display = "block";
  }
}

function showLoadingSpinner() {
  document.getElementById("loading").style.display = "block";
}
function hideLoadingSpinner() {
  document.getElementById("loading").style.display = "none";
}