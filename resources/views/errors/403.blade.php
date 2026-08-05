@extends('layouts.error')

@section('code', '403')
@section('title', 'Forbidden')
@section('message', config('app.debug') && $exception->getMessage() ? $exception->getMessage() : 'Forbidden')
