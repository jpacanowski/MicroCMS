(function() {

    'use strict';

    document.querySelector('.menubar ul li.dropdown').onclick = function() {
        document.querySelector('.menubar ul li.dropdown').classList.toggle('clicked');
        document.querySelector('.menubar ul li.dropdown ul').classList.toggle('clicked');
    };

    let array_tags = []
    const $ul_tagsList = document.querySelector('ul.tags_list');
    const $input_postTags = document.querySelector('input[id="post_tags"]');
    const $input_enterTags = document.querySelector('input[id="enter_tags"]');

    if(document.body.contains($input_enterTags)) {

        $input_enterTags.addEventListener("keypress", function(e) {
        
            if (e.keyCode == 13) {
                
                e.preventDefault();
                e.stopPropagation();
                
                const el = document.createElement('li');

                el.className = 'tags_item';
                el.innerHTML = this.value + "<i class='fa fa-remove' data-tag='" + this.value + "'></i>";

                $ul_tagsList.appendChild(el);

                array_tags.push(this.value);

                this.value = '';

                $input_postTags.value = array_tags.join(',');

                el.querySelector('.fa-remove').addEventListener('click', function(e) {
                    
                    let tag = this.dataset.tag;
                    let index = array_tags.indexOf(tag);
                    array_tags.splice(index, 1);

                    this.parentElement.remove();

                    $input_postTags.value = array_tags.join(',');
                });
            }

        });
    }

})();