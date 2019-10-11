<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
return view('welcome'); 
}); 

Auth::routes(); 

Route::get('/home', 'HomeController@index'); 

Route::get('/proposal_new', 'ProposalController@index'); 
Route::post('/proposal/draft', 'ProposalController@createDraft'); 

//Route::post('/create_draft/', 'ProposalController@createDraft'); 
Route::post('/proposal/{proposal}', 'ProposalController@createProposal'); 
Route::get('/proposal/{proposal}/pdf', 'ProposalController@generatePDF');
Route::get('/proposal/{proposal}/pdf_styled', 'ProposalController@generateStyledPDF');

Route::get('/list_proposals', 'ProposalController@list'); 
Route::get('/proposal/{proposal}/edit', 'ProposalController@edit'); 
Route::get('/proposal/{proposal}/view', 'ProposalController@viewProposal'); 

Route::post('/proposal/{proposal}/delete', 'ProposalController@delete'); 

Route::post('/edit_details/{proposal}', 'ProposalController@updateDetailsProposal'); 

Auth::routes(); 

Route::get('/list_functionalities', 'FunctionalityController@index'); 

Route::get('/functionality', 'FunctionalityController@add'); 
Route::post('/functionality', 'FunctionalityController@create'); 
Route::get('/functionality/{functionality}', 'FunctionalityController@edit'); 
Route::post('/functionality/{functionality}', 'FunctionalityController@update'); 

/** */





