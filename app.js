import { Pokemon, Lucha } from './pokemon.js';

function buscaPokemon() {
    let pokemonNameInput = document.getElementById('textoInput');
    let pokemonName = pokemonNameInput.value.trim();

    if (pokemonName === "") {
       console.log("Error al introducir pokemon");
        return;
    }

    fetchData(pokemonName);
}

window.buscaPokemon = buscaPokemon;

window.fetchData = async (textoPokemon) => {
    try {
        const res = await fetch(`https://pokeapi.co/api/v2/pokemon?limit=1000`);
        const data = await res.json();
        
        const pokemonList = data.results;
        const pokemonFiltrados = pokemonList.filter(pokemon => pokemon.name.startsWith(textoPokemon.toLowerCase()));

        if (pokemonFiltrados.length === 0) {
            const boton = document.getElementById('listaBotones');
            boton.setAttribute('display', 'none');
            return;
        }

        const boton = document.getElementById('listaBotones');
        boton.innerHTML = '';

        for (let pokemon of pokemonFiltrados) {
            let pokeBoton = document.createElement('button');
            pokeBoton.textContent = pokemon.name;
            pokeBoton.addEventListener('click', async () => {

                const pokemonData = await fetchPokemonData(pokemon.url);
                const objectPokemon = convertToObjectPokemon(pokemonData);
                pintarCard(objectPokemon);

            });
            boton.appendChild(pokeBoton);
        }
        
    } catch (error) {
        console.log(error);
    }
}


async function fetchPokemonData(url) {
    try {
        const res = await fetch(url);
        return await res.json();
    } catch (error) {
        console.log(error);
    }
}

function convertToObjectPokemon(datos){

    let poke1 = new Pokemon(datos.name);
    poke1.experience = datos.base_experience;
    poke1.hp = datos.stats[0].base_stat;

    let luchaPok1 = new Lucha(
        datos.stats[1].base_stat,
        datos.stats[2].base_stat,
        datos.stats[3].base_stat);
    poke1.lucha = luchaPok1;

    poke1.id = datos.id;
    poke1.img = `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${datos.id}.png`
            

    console.log(poke1);
    return poke1;
}

function pintarCard(objectPokemon){
    let carta = document.getElementById("carta");
    let img = document.getElementById("img");
    let divNombre = document.getElementById("pokenombre");
    let id = document.getElementById("idpokemon");
    let h3=document.getElementById("selected");
    id.innerHTML=objectPokemon.id;
    h3.innerHTML="Pokémon seleccionado:"
    // h3.setAttribute('value', objectPokemon.id);
    img.src = objectPokemon.img;
    img.removeAttribute("hidden");
    divNombre.innerHTML = objectPokemon.name;
    carta.removeAttribute("hidden");
    //divNombre.setAttribute('value', objectPokemon.name);

    //Metemos datos
    let idhidden = document.getElementById("idhidden");
    idhidden.setAttribute('value', objectPokemon.id);
    let namehidden = document.getElementById("namehidden");
}
