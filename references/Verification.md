# The Verification Class
<hr/>

**Notice: This will update when the code changes as the content will not be final**

This class extends from the `VerificationUtility` class which in turn handles the most basic validation functions.
The `VerificationUtility` class is used for basic handling with the base assumption that the `Verification` class passed
the correct information. In order to talk about the `Verification` class, we need to first dive into the mechanics of the
`VerificationUtility` class.

## The functions
<hr/>

### `issetAndNotEmpty`
The name pretty much explains it. It checks if the value given is 
set and is not a blank item such as "" or []. Pretty basic and only takes one line of code. It accepts any type of value
to be checked and returns a boolean value.

### `checkType`
It's also very simple and just returns a lowercase string of the type of 
argument passed to it. Available returns are string, integer, double, array, object, null, boolean and more. It accepts any
type of value as an argument.

### `returnTrueIfBlackArray`
Again, returns true if the array is blank or else the argument will be returned. It only accepts an array as an argument

### `checkReturns`
It checks the contents of the array, if a `false` value is found inside the array, false will be returned and true is the 
returned value if no `false` is found.

### `checkEmailReturnedJson`
It checks if the email check returned is true or not. It accepts a _(json)_ object as an argument and returns true if it
is not disposable, its format is valid. It originally checks more values but for some reason the api is not working. I 
do not have the time and resources and knowledge to develop one so you got to do with what you have right, 
"beggars can't be choosers". 

### `isEmail`
This checks the argument it receives whether if it is an email, true if it is, false otherwise. It uses the regular
expression of `"/[a-zA-Z\d+_.-]+@[a-zA-Z\d.-]/"` to determine whether the value is valid or not.

### `regexMatch`
This function takes two arguments, both required. The first is the `mixed` type `value` which is going through the regex
check. The second argument is an `array` which carries the attributes for the regex function. It holds the pattern, flip 
and message. The pattern can be set to `default` which means it will pass a predefined pattern which **checks for special**
**characters**. The `flip` in the `attr` array can be undefined and is set to false by default. If it is true, the result
will be reversed. 

_This is currently the end of the `VerificationUtility` but there is no guarantee more functions won't be added in the 
future_

**Now moving on to the actual complex `Verification` class. Only two methods are set to public**

### Public Method 1: `validate`
Nearly the only one method needed _(others are helper functions but due to being more complex are being put here)_
This function takes quite a few arguments. I admit my design patterns aren't the best but this is what I will make do with.

The first argument is the `content` being checked and can be any type of value. <br/> 
The second argument is the `name` of the value being evaluated, this is used in the `returnErrorMessage` function. <br/> 
The third argument is the `attr` which specifies what tests should the `content` being passed through. <br/>
The fourth argument is the `autoReturnMsg` and it is set to true by default. If this is false, it will return the array 
of true and false arguments based on the test it passed through. However, if it is true it will return an array of error 
messages if any.

First, the function will loop through everything in the `attr` argument and get the name of the test to be executed. 
If it is an array, the array key of `errMsgAttr` will be set to the `test_name` as returned by the `arrayValidation` function.
Then, the returns array's key of the `test_name` will be set to the bool value. 
If it is not an array, the function will pass the test name and the contents to be checked to the 
`staticValidation` method and it will return an array with a bool value, whether it passed the check or not. After that,
the returns key will be set to the test's name and the value is the bool value returned. After looping finish, if 
`autoReturnMsg` is set to true, it will return the returns of the function `returnErrorMessage` or else it will return the
array of returns which is the key value pair of the `test_name => value`

### Public Method 2: `returnErrMsg`
This functions accepts three arguments, two required the `attr` argument optional

For each value in the array of validated tests, it is dissected into the item and value pair. If the value is false, we 
check the test's name and return the appropriate error message. However, in the case of length, we check if there is a 
length's value of min and max in the `attr` array. If there is, the message will be based on it if not a default number 
will be returned. 

The first argument `arr` is an array of the key value pair of error returned by the validate function if the `autoReturnMsg`
is turned off. However, the `validate` function's `autoReturnMsg` will execute this function without needing to call it 
again. <br/>
The second argument is the name of the value being checked, mainly used in the error messages. <br/>
The third argument, `attr` array is a vector that mainly looks like `[ length => [ min, max] ]`

After that, a call to the `returnTrueIfBlankArray` function which will has been explained in the `VerificationUtility` 
class. The return will be either a bool _(only true will be returned if return type is a bool)_ or the array of errors.

### Private Function 1: `staticValidation`
This function accepts two required arguments, one is the test name, it must be of type`string`, another is the content 
being checked with the type `mixed`.

Quite simple and straightforward, there is just one statement in the entire function. It uses the match control structure
to return a value based on the `item` which is the test name to it. It then executes the appropriate function associated 
with the test name and passes the `content`. This function return value is strictly a bool and if the test name does not 
match anything found, it would return false by default and an error will **NOT** be thrown so please take note.

This function is currently only used in the `validate` function

### Private Function 2: `arrayValidation`
This function accepts two required arguments, one is the test name and its options, it must be of type `array` The item 
array is an array consisting of `[ test_name, ...optional_arguments ]`, another is the content being checked with the 
type `mixed`. 

The length variables will be set prior to the validation to reduce complexity later on due to the nature of the `match`
statement. Once those are set, we lower the first item in the `item` argument which is the test name. According to the 
test name, the functions are called and an array will be returned. 

By default, it is an `undefined` check and the value will always be `false`. 

For the check _length_:
- the `item` is an array, `[ test_name, (mix), (max) ]` with min and max being optional
- the return is an array, `[ test_name, bool_value_of_check, array_of_min_max ]`


For the check _match_:
- the `item` is an array, `[ test_name, NOVBCA, value_checked_against ]`
- the return is an array, `[ test_name, bool_value_of_check, NOVBCA ]`

###### *NOVBCA = name of value being checked against

### Private Function 3: `checkLength`
This function takes in a required `mixed` type `content` argument and two more optional arguments which are the 
min and max value, both with the type `int`. The default value is set to 0 and 256 and this function will be returning 
a bool value.

If the `content` is set and not empty, we will check the `content` for its type. Depending on the type of the `content`, 
we evaluate the lengths differently. If it is a `string` or `integer` or `double`, we count the number of characters in it.
However, if it is an array, we will return the size of the array using the `sizeof` builtin function. 

### Private Function 4: `checkEmail`
This function takes only a string argument which is the email to be checked.

It calls the `emailAPIReturn` function to get the returned values from the api call and converts it into object as the 
return of the `emailAPIReturn` function will return a JSON string to be parsed. Then, it will return the returns of the
function `checkEmailReturnedJson` which is defined in the `VerificationUtility` class. It will always return a bool value.

### Private Function 5: `checkMatch`
This function accepts two required `content` with the `mixed` type to be checked and the `cntTwo` with the type `mixed`
which means _content two_. The optional argument is the `strict` argument which will do a strict check based on the bool
value it received. By default, it is set to `false`

It does a comparison and returns the result.

### Private Function 6: `emailAPIReturn`
It accepts a `string` type argument and does a curl request based on the current API implementation. It returns the 
response of the curl request as a `string`. It may vary depending on the API of choice. Just copy and paste the example
from the documentation and return the response if there is any need to change the API might it not be working or others.

<hr/>

**That's the end of the Verification class currently. If you made it here, thanks for listening to me ranting on and on.**