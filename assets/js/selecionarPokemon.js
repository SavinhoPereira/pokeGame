document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("characters");
    const selectedList = document.getElementById("selected-characters");
    const hiddenInput = document.getElementById("hidden-characters");

    let selectedCharacters = []; 

    function updateSelectedCharacters() {
        selectedList.innerHTML = ""; 

        selectedCharacters.forEach(value => {
            const li = document.createElement("li");
            li.textContent = value;

            // Criando botão de remoção
            const removeButton = document.createElement("button");
            removeButton.textContent = "❌";
            removeButton.onclick = () => removeCharacter(value);

            li.appendChild(removeButton);
            selectedList.appendChild(li);
        });

        hiddenInput.value = selectedCharacters.join(","); 
    }

    function addCharacter() {
        const selectedValues = Array.from(select.selectedOptions).map(option => option.value);

        selectedValues.forEach(value => {
            if (!selectedCharacters.includes(value) && selectedCharacters.length < 5) {
                selectedCharacters.push(value);
            }
        });

        updateSelectedCharacters();
    }

    function removeCharacter(character) {
        selectedCharacters = selectedCharacters.filter(c => c !== character);
        updateSelectedCharacters();
    }

    select.addEventListener("change", addCharacter);
});
