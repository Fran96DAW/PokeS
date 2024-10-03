export class Pokemon{
    name;
    experience;
    hp;
    img;
    lucha;
    id;

    constructor(name){
        this.name=name;
    }

    describe(){
        console.log(this.name);
        console.log(this.experience);
        console.log(this.hp);
        console.log(this.img);
        console.log(this.lucha);
    }
}

export class Lucha{
    ataque;
    defensa;
    especial;
    constructor(ataque,defensa,especial){
        this.ataque=ataque;
        this.defensa=defensa;
        this.especial=especial;
    }
}

export default Pokemon;