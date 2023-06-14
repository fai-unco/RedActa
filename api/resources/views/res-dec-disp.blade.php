@php( $issuerName = $document->issuer->description )
@php( $issuerId = $document->issuer->id )
@php( $documentTypeName = $document->documentType->description )
@php( $documentTypeId = $document->documentType->id )
@php( $name = $document->name )
@php( $number = $document->number )
@php( $months = ["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"] )
@php( $issuePlace = $document->issue_place )
@php( $issueDate = strtotime($document->issue_date) )
@php( $issueDateStr = date('d', $issueDate)." de ".$months[date('n', $issueDate)-1]. " de ".date('Y', $issueDate) )
@php( $adReferendum = $document->ad_referendum )
@php( $body = json_decode($document->body) )
@php( $headers = [1 => "decanato" , 2 => "consejo_directivo"] )
@php( $documentTypeAction = [1 => "RESUELVE" , 2 => "DECLARA", 3 => "DISPONE"] )
@php( $issuerInArticlesSectionBeggining = [1 => "DECANO" , 2 => "CONSEJO DIRECTIVO"] )
@php( $decanatoData = ["Lic. Guillermo Grosso", "Decano"] )
@php( $intToRomanNumbers = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X'])

<!DOCTYPE html>
<html>
	<head>
		<title>{{$name}}</title>
    	<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="https://latex.now.sh/style.css">
		<link rel="stylesheet" href="{{ env('STATIC_FILES_DIRECTORY').'/assets/css/document.css' }}">
		<link rel="stylesheet" href="{{ env('STATIC_FILES_DIRECTORY').'/assets/css/res-dec-disp.css' }}">
	</head>
	<body>
		<table class="document-container">
			<thead class="document-header">
				<tr>
					<th>
						<div class="top-header">
							<img src="{{ env('STATIC_FILES_DIRECTORY').'/assets/images/headers/'.date('Y', $issueDate).'/'.$headers[$issuerId].'.png' }}">
						</div>
						<div class="subheader">
							{{mb_strtoupper($documentTypeName, 'UTF-8')}} {{mb_strtoupper($issuerName, 'UTF-8')}} FAIF N° {{sprintf('%03s', $number)}}<br>
							{{mb_strtoupper($issuePlace, 'UTF-8')}}, {{$issueDateStr}}
						</div>
					</th>
				</tr>
			</thead>
			<tbody class="document-body">
				<tr>
					<td class="document-body-cell">
						<div class="visto-section indented">
							<b class="visto-section-beggining">VISTO</b>, {!! $body->visto !!}; y,
						</div>	
						<div class="considerando-section indented">
							<b>CONSIDERANDO:</b>
							<div class="considerando-item">
								@foreach($body->considerando as $considerando_item)
									{!! $considerando_item !!}										
								@endforeach
							</div>
							<p>Por ello</p>
						</div>
						<div class="articulos-section">
							<div class="articulos-section-beggining">
								<b> 
									<div class="no-wrap">
										EL 
										{{ $issuerInArticlesSectionBeggining[$issuerId] }}
										DE LA FACULTAD DE INFORMÁTICA
									</div>
									DE LA UNIVERSIDAD NACIONAL DEL COMAHUE<br> 
									@if($adReferendum)
										AD REFERENDUM DEL CONSEJO DIRECTIVO <br>
									@endif	
									{{ $documentTypeAction[$documentTypeId] }}:
								</b>
							</div>
							@foreach($body->articulos as $articulo)
								<div class="articulo">
									<b class="articulo-number no-wrap">
										ARTÍCULO {{ $loop->index + 1}}°:
									</b>
									<div class="articulo-content">
										{!! $articulo !!}									
									</div>
								</div>
							@endforeach
						</div>
						<div class="anexos-section">
							@php(chdir(env('STATIC_FILES_DIRECTORY').'/uploads'))
							@foreach($anexos as $key=>$anexo)
								<div class="anexo-content">
									<p><b>ANEXO {{sizeof($anexos) > 1 ? $intToRomanNumbers[$key+1] : 'ÚNICO'}}</b></p>
									@php($numberOfPages = (int)shell_exec('set -- '.$anexo->file->id.'-* ; echo "$#"'))
									@for($i = 1; $i <= $numberOfPages; $i++)
										<img src="{{ env('STATIC_FILES_DIRECTORY').'/uploads/'.$anexo->file->id.'-'.$i.'.png'}}" @class([
											'is-copy-img' => $isCopy,
											'normal-img' => !$isCopy
										])>
									@endfor
								</div>
							@endforeach
						</div>
					</td>
				</tr>
			</tbody>
			@if($isCopy)
				<tfoot class="document-footer">
					<tr>
						<td class="document-footer-cell">
							<p>ES COPIA FIEL</p>
							<div style="display: flex; flex-flow: row-reverse;">
								<p style="text-align: center">
									Fdo. {{$decanatoData[0]}} <br>
									{{$decanatoData[1]}}
								</p>
							</div>
						</td>
						<td class="document-footer-empty-cell"></td>
					</tr>
				</tfoot>
			@endif
		</table>	
		@if($isCopy)
			<div class="footer">
				<p>ES COPIA FIEL</p>
				<div style="display: flex; flex-flow: row-reverse;">
					<p style="text-align: center">
						Fdo. {{$decanatoData[0]}} <br>
						{{$decanatoData[1]}}
					</p>
				</div>
			</div>
		@endif
	</body>		
</html>