<!DOCTYPE html>
<html >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    

    </head>
    <body>
        
        <h1 h2 @style([
            'font-size: 24px'
        ])>Wiadomość od {{$data['mail']}} </h1>
        @if($user)
        
        <h2 h2 @style([
            'font-size: 20px'
        ])>ID użytkownika {{$user->id}} </h2>
        @endif
        <b>Tytuł użytkownika {{$data['sirOrLady']}} </b>
        <div @style([
            'padding: 12px',
            'border: 2px solid black',
            'border-radius: 10px'
        ])>
        <h2 @style([
            'font-size: 20px'
        ])>Treść wiadomości</h2>
        <p @style([
            'margin: 12px 0px'
        ])>{{$data['message']}} </p>
       
        <div>
    </body>
</html>
