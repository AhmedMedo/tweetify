<?php $fruits = ['Apple', 'Banana', 'Orange']; ?> 

<h1>Fruits</h1> 

<ul> 
   <?php foreach ($fruits as $fruit) { ?> 
   <li><?= $this->escaper->escapeHtml($fruit) ?></li> 
   <?php } ?> 
</ul>  

<?php $robots = ['Voltron', 'Astro Boy', 'Terminator', 'C3PO']; ?>  

<ul> 
   <?php foreach ($robots as $robot) { ?> 
   <li><?= $robot ?></li> 
   <?php } ?> 
</ul>