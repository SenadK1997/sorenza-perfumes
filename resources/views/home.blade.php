@extends('layouts.app')

@section('title', 'Sorenza Parfemi | Luksuzni Originalni Parfemi Online - BiH, Hrvatska, Srbija')

@section('meta_description', 'Sorenza - Vaša premium online parfumerija. Kupite originalne luksuzne parfeme po povoljnim cijenama. Širok izbor muških, ženskih i unisex mirisa. Brza dostava u BiH, Hrvatskoj i Srbiji. ✓ Originalni proizvodi ✓ Sigurna kupovina')

@section('meta_keywords', 'sorenza parfemi, luksuzni parfemi, originalni parfemi, parfemi online, parfumerija, parfemi BiH, parfemi Hrvatska, parfemi Srbija, muški parfemi, ženski parfemi, unisex parfemi, kupovina parfema online, brza dostava parfema')

@section('og_title', 'Sorenza Parfemi | Luksuzni Originalni Mirisi za Nju i Njega')
@section('og_description', 'Otkrijte svijet luksuznih mirisa u Sorenza parfumeriji. Originalni brendovi, jedinstvene arome i povoljne cijene. Brza dostava širom BiH, Hrvatske i Srbije. 💎')
@section('og_image', asset('images/sorenza-og.jpg'))

@section('twitter_title', 'Sorenza Parfemi | Premium Parfumerija Online')
@section('twitter_description', 'Luksuzni originalni parfemi na jednom mjestu. Muški, ženski i unisex mirisi. Brza dostava u BiH, HR, SRB. ✨')
@section('twitter_image', asset('images/sorenza-og.jpg'))

@section('content')
    <x-hero-section />
    <x-gender-sales />
    <x-become-partner />
    <x-why-sorenza />
@endsection
