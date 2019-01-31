# Front-end-plugin som skapar en array för 'Hitta på sidan'

## Hur man använder Region Hallands plugin "region-halland-find-on-page"

Nedan följer instruktioner hur du kan använda pluginet "region-halland-find-on-page".


## Användningsområde

Denna plugin hämtar ut alla h2, h3 och h4-rubriker och placerar dom i en array.
Är "Modularity" installerad hämtas även alla rubriker därifrån och läggs i slutet av arrayen

OBS! Du behöver även ha installerat och aktiverat Region Hallands plugin "region-halland-prepare-the-content" för att denna plugin ska fungera.


## Installation och aktivering

```sh
A) Hämta pluginen via Git eller läs in det med Composer
B) Installera Region Hallands plugin i Wordpress plugin folder
C) Aktivera pluginet inifrån Wordpress admin
```


## Hämta hem pluginet via Git

```sh
git clone https://github.com/RegionHalland/region-halland-find-on-page.git
```


## Läs in pluginen via composer

Dessa två delar behöver du lägga in i din composer-fil

Repositories = var pluginen är lagrad, i detta fall på github

```sh
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/RegionHalland/region-halland-find-on-page.git"
  },
],
```
Require = anger vilken version av pluginen du vill använda, i detta fall version 1.0.0

OBS! Justera så att du hämtar aktuell version.

```sh
"require": {
  "regionhalland/region-halland-find-on-page": "1.0.0"
},
```


## Visa "find-on-page" på en sida via "Blade"

```sh
@if(function_exists('get_region_halland_find_on_page'))
	@php($myNavs = get_region_halland_find_on_page())
	@if(isset($myNavs) && count($myNavs) > 0)
		@php($id = uniqid())
		<div id="content-nav-placeholder"></div>
		<nav class="content-nav-container rh-get-sticky" id="content-nav-container">
			<div>
				<h4 id="{{ $id }}">Hitta på sidan</h4>
				<ul>
					@foreach ($myNavs as $myNav)
						<li>
							<a href="#{{ $myNav['slug'] }}" data-pointstoid="{{ $myNav['slug'] }}">
								{!! $myNav['content'] !!}
							</a>
							<meta itemprop="position" content="{{ $loop->iteration }}" />
						</li>
					@endforeach
				</ul>
			</div>
		</nav>
	@endif
@endif
```


## Exempel på hur arrayen kan se ut

```sh
array (size=2)
  0 => 
    array (size=3)
      'tag' => string 'h2' (length=2)
      'slug' => string 'lorem-ipsum' (length=11)
      'content' => string 'Lorem ipsum' (length=11)
  1 => 
    array (size=3)
      'tag' => string 'h2' (length=2)
      'slug' => string 'lorem-ipsum-dolares' (length=19)
      'content' => string 'Lorem ipsum dolares' (length=19)
```


## Css med fixed properties + för highlight

```sh
.rh-get-sticky {
    position: -webkit-sticky;
    position: sticky;
    top: 15px;
}

.rh-get-fixed-sticky {
    position: fixed;
    top: 15px;
}

.content-highlight {
  -webkit-animation-name: content-hightlight-animation;
       -o-animation-name: content-hightlight-animation;
          animation-name: content-hightlight-animation;
  -webkit-animation-duration: 1.5s;
       -o-animation-duration: 1.5s;
          animation-duration: 1.5s;
  -webkit-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
       -o-animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
          animation-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
  -webkit-animation-iteration-count: 1;
       -o-animation-iteration-count: 1;
          animation-iteration-count: 1;
}
```


## Jquery find-on-pgae scroll funktion

```sh
$('a[href^="#"]').on( "click", function() {
var target = $(this.hash);
    if (target.length) {
        
        // Animate target
        $('html,body').animate({scrollTop: target.offset().top}, 1000);
        
        // Add class for highlight the text
        $(target).addClass("content-highlight");
        
        // Wait 1.5 s and then remove the highlight class
        setTimeout(function(){
            $(target).removeClass("content-highlight");
        }, 1500);
        
        return false;
    }
});
```


## Jquery för att fixa fixed position även med IE

```sh
if ($("body.page-template-default")[0]){
    $(window).scroll(function() {
        var myContentPosition = Math.round($('#content-nav-placeholder').offset().top);
        var myPosition = Math.round($('#content-nav-placeholder').offset().top - $(window).scrollTop());
        if (myPosition < 30) {
            $("#content-nav-container").addClass("rh-get-fixed-sticky");
        } else {
            $("#content-nav-container").removeClass("rh-get-fixed-sticky");
        }
    });   
}
```


## Versionhistorik

### 1.0.0
- Första version
