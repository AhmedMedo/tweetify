{% set fruits = ['Apple', 'Banana', 'Orange'] %} 

<h1>Fruits</h1> 

<ul> 
   {% for fruit in fruits %} 
   <li>{{ fruit|e }}</li> 
   {% endfor %} 
</ul>  

{% set robots = ['Voltron', 'Astro Boy', 'Terminator', 'C3PO'] %}  

<ul> 
   {% for robot in robots %} 
   <li>{{ robot }}</li> 
   {% endfor %} 
</ul>