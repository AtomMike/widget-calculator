<?php

/**
 * The template which displays the calculator markup
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Widget_Calculator
 * @subpackage Widget_Calculator/public/partials
 */
?>

<div class="calculator-container">
    
    <div class="form form-calculator calculator-container--part">
       <form action="">
           <div class="form form--row">
               <label for="quantity"></label>
               <input type="number" name="calc_input_quantity" id="calc_input_quantity" placeholder="Enter a quantity">
           </div>
           <div class="form form--row">
               <button id="calculator_submit">Calculate</button>
           </div>
       </form>
    </div>
    
    <div class="card card--results calculator-container--part">
        <p>Number of packs required</p>
        <p id="packs_total_output"></p>
    </div>
</div>