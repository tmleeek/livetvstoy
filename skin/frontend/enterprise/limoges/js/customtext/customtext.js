/**
 * Created by johnney.cha on 1/21/2015.
 *
 * NOTE: Javascript layered with PrototypeJS
 *
 */
// CONSTANT DECLARATIONS
// TODO: temporary variable that will be replaced by a variable from the .phtml file which will be assigned by the admin

if(is_shared_custom_option != 0) {
var shared_custom_option_ids_array = shared_custom_option_ids.split(",");   //Split the array to get custom field Id's
var TOTAL_CHAR_MAX = max_char_length_option;                                //Sum of Field's Max Characters


// VARIABLES
var max_warning_msg;
var add_to_cart_btn;
var total_char = 0;
var customId = 0;

var textboxcollections = $$('div.input-box > input');               // find all divs with a child tag 'input' class name 'input-box'

// STRINGS
msg = 'character count: ';
max_warning_msg = '<br/><p style="color: palevioletred;" id="max_msg">**Max number of characters reached ('+TOTAL_CHAR_MAX+' characters)**</p>';



// finds all tag names with input with the class options_X_text and
// generates a customID under the attribute 'data_customId'
textboxcollections.each(function(inputbox)
{
    var string = inputbox.inspect()
    var arrayOfStrings = string.split(' ');
    var inputBoxTagName = arrayOfStrings[0].substring(1, 6);
    var inputBoxClassFirst = arrayOfStrings[1].substring(4, 11);
    var inputBoxClassSecond = arrayOfStrings[1].substring(arrayOfStrings[1].length-5, arrayOfStrings[1].length-1);
    


    if( inputBoxTagName === 'input' && inputBoxClassFirst === 'options' &&  inputBoxClassSecond === 'text')
    {
        //Custom code to get Custom option field value.
        //12 = id="options_
        //-6 = _text"
        var customOptionFieldId = arrayOfStrings[1].substring(12, arrayOfStrings[1].length-6);
        //Custom code to apply data_customId attribute for field got from custom_option_shared_ids_array array.
        if(shared_custom_option_ids_array.indexOf(customOptionFieldId) != -1){
            inputbox.writeAttribute('data_customId', 'custom_'+customId);
            customId++;
        }

    }
})

var buttonCol = $$('button');                                       // find all the buttons

for (var i = 0; i < buttonCol.length; i++) {                        // searches for the add cart button assign to a variable
    if (buttonCol[i].className === 'button btn-cart red-button') {
        add_to_cart_btn = buttonCol[i];
    }
}
var inputboxes = $$('input:[data_customId]');                       // finds the element with the attribute 'data_customId'
var errorFlag = false;
var total_char_arr = new Array(inputboxes.length);                  // create new array with the size of the amount of
                                                                    // custom input text by admin

//$('product-options-wrapper').insert(max_warning_msg);               // create and insert the warning msg div
$('product-options-wrapper').insert({top: max_warning_msg});        // create and insert the warning msg div at the top
$('max_msg').hide()                                                 // hide the warning msg

inputboxes.each(function(textInputBox){
    Event.on(textInputBox, 'input', function(){

        var data_customIdString = this.readAttribute('data_customId');                                  // customID value
        var index = data_customIdString.substring('custom_'.length, data_customIdString.length);        // finds the current element's index in array by using the custom ID

        total_char_arr[index] = this.value.length;                  // length of the input text value

        //alert(total_char_arr[index]);                             // for debugging purposes

        total_char_arr.each(function(char_val)                      // for each total char array
        {
            total_char += char_val;                                 // determines the total num char
        })

        //alert(total_char);                                        // for debugging purposes


        if(total_char <= TOTAL_CHAR_MAX)                             // if total_char < TOTAL_CHAR_MAX enable add cart button
        {
            add_to_cart_btn['disabled'] = false;                        // disable = false
            if($('max_msg').visible()) {                                // if element with id max_msg is visible hide
                $('max_msg').hide();                                    // hide element
                errorFlag = false;                                       //this will tell validation occurs
            }
        }else {                                                     // else disable add cart button
            add_to_cart_btn['disabled'] = true;                         // disable = true
            if(!$('max_msg').visible()) {                               // if element with id max_msg is not visible show
                $('max_msg').show();                                    // show element
                errorFlag = true;                                       //this will tell validation occurs
            }
        }

        if (errorFlag) {
            inputboxes.each(function(customInputBox){
                //this will highlight input box border
                customInputBox.setStyle({
                    border: '1px solid #ff0000'
                });
            });
        } else {
            inputboxes.each(function(customInputBox){
                //this will add default border style
                customInputBox.setStyle({
                    border: '1px solid #b5b5b5'
                });
            });
        }

        total_char = 0;                                             // resets variable
    })

});
}