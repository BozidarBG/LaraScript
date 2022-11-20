@extends('layouts.app')
@section('styles')
    <style>

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <h1>naslov</h1>
            <pre id="pre">

            </pre>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        c=console.log;
        let x="khj";
        function returnNumber(x){
            return Number(x.replaceAll(/[^\d]/gi, ""));
        }
        //c( returnNumber(x))
        //c(typeof returnNumber(x))

        let yy="123456789";
//c(yy.length)
        function returnRound(num, zeros){
            let string=String(returnNumber(num));
            if(num.length<zeros){
                zeros=num.length-1;
            }
                return Number(string.substr(0, string.length-zeros)+"0".repeat(zeros));
        }
        c("zaokriženi na 3 nule je ",returnRound(yy, 3));
        c("zaokriženi na 2 nule je ",returnRound(yy, 2));


        function returnWithSeparator(num){
            let arr_of_numbers=String(num).split("");
            let new_arr=[];
            let temp_i=0;
            for(let i=arr_of_numbers.length-1; i>=0; i--){//in reversed order
                if(temp_i===3){
                    new_arr.push(".");
                    temp_i=1;
                }else{
                    temp_i++;
                }
                new_arr.push(arr_of_numbers[i]);
            }
            return new_arr.reverse().join("");

        }
        c("sa separatorima",returnWithSeparator(yy));
        c("sa separatorima i 3 nule", returnWithSeparator(returnRound(yy, 3)))
    </script>
    <!--
    <script id="a">
        let c=console.log;
        //for in
        // (A) ITERATE OBJECT
        var person = {
            name : "Jon Doe",
            email : "jon@doe.com"
        };
        for (let key in person) {
            console.log(key); // name, email
            console.log(person[key]); // jon, jon@doe.com
        }
c("********************")
        // (B) ITERATE ARRAY
        var animals = ["Birb", "Cate", "Doge"];
        for (let i in animals) {
            console.log(i); // 0, 1, 2
            console.log(animals[i]); // birb, cate, doge
        }
        c("********************")

        // (C) ITERATE STRING
        var hello = "world";
        for (let i in hello) {
            console.log(i); // 0, 1, 2, 3, 4
        }
        c("********************")

        /*
        The for-in loop will run through the keys:

Properties of an object.
The index of arrays.
Character position of a string.

         */

        // (A) ITERATE OBJECT - ERROR, NOT ITERABLE
        var obj = { "foo" : "bar" };
        for (let value of obj) {
          console.log(value);
        }

        // (B) ITERATE ARRAY
        var animals = ["Birb", "Cate", "Doge"];
        for (let value of animals) {
            console.log(value); // birb, cate, doge
        }

        // (C) ITERATE STRING
        var hello = "world";
        for (let char of hello) {
            console.log(char); // w, o, r, l, d
        }

        // (A) PERSON OBJECT
        var person = {
            "Name" : "Jon",
            "Email" : "jon@doe.com"
        };

        // (B) LOOP OBJECT ENTRIES
        for (let [key, value] of Object.entries(person)) {
            console.log(key); // name, email
            console.log(value); // jon, jon@doe.com
        }


        // 2. Find an object in Array
        const employess = [
            {name: "Paul", job_title: "Software Engineer"},
            {name: "Peter", job_title: "Web Developer"},
            {name: "Harald", job_title: "Screen Designer"},
        ]
        let sen = employess.find((data) => {
            return data.job_title === "Software Engineer";
        });


        console.log(sen) // { name: 'Paul', job_title: 'Software Engineer' }

        // 6. Get Rid of Duplicates
        function removeDuplicates(array) {
            return [...new Set(array)];
        }
        const uniqueStr = removeDuplicates(["Paul", "John", "Harald", "Paul", "John"])
        const uniqueNr = removeDuplicates([1, 1, 2, 2, 3, 3, 4, 5, 6, 7, 7, 7, 9])
        console.log(uniqueStr) // [ 'Paul', 'John', 'Harald' ]
        console.log(uniqueNr) // [1, 2, 3, 4, 5, 6, 7, 9]

        let source=document.getElementById('a').innerText;
        //c(source)
        document.getElementsByTagName('pre')[0].innerText=source;
    </script>
    -->
@endsection
