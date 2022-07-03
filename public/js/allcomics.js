const marvelPublicKey="6305a351d42a1d5c61da128b14f98648",
marvelPrivateKey = "15bb1d1f158d3d057f5c5efa4dd7cea5d04a5f60",
hashKey = "452cead3353da476bdeb42984363e0d1";

function limit (string = '', limit = 0) {  
    return string.substring(0, limit)
}

const marvel = {
    render: () => {
      const urlAPI = `https://gateway.marvel.com/v1/public/comics?ts=1&apikey=${marvelPublicKey}&hash=${hashKey}`;
      const container = document.querySelector('#list-heroes');
      let contentHTML = '';
  
      fetch(urlAPI)
        .then(res => res.json())
        .then((json) => {
          for (const hero of json.data.results) {
            let urlHero = hero.urls[0].url;
            contentHTML += `
              <div class="col-md-4">
                  <a href="${urlHero}" target="_blank">
                    <img src="${hero.thumbnail.path}.${hero.thumbnail.extension}" alt="${hero.title}" class="img-thumbnail">
                  </a>
                  <h3 class="title">${hero.title}</h3>
              </div>`;
          }


          container.innerHTML = contentHTML;
        })
    }
  };
  marvel.render();