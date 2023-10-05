@php( $issuer = $document->issuer )
@php( $documentType = $document->documentType )
@php( $name = $document->name )
@php( $number = $document->number )
@php( $months = ["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"] )
@php( $issueDate = strtotime($document->issue_date) )
@php( $issueDateStr = date('d', $issueDate)." de ".$months[date('n', $issueDate)-1]. " de ".date('Y', $issueDate) )
@php( $adReferendum = $document->ad_referendum )
@php( $hasAnexoUnico = $document->has_anexo_unico )
@php( $body = json_decode($document->body) )
@php( $intToRomanNumbers = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X'])
@php( $issuerSettings = $issuer->issuerSettings )
<!DOCTYPE html>
<html>
	<head>
		<title>{{$document->name}}</title>
    	<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="https://latex.now.sh/style.css">
		<link rel="stylesheet" href="{{ asset('/assets/css/document.css') }}">
		<link rel="stylesheet" href="{{ asset('/assets/css/res-dec-disp.css') }}">
	</head>
	<body>
		<table class="document-container">
			<thead class="document-header">
				<tr>
					<th>
						<div class="top-header">
							<img src="{{ env('STATIC_FILES_DIRECTORY').'/uploads/'.$fileId.'.png' }}">
						</div>
						<div class="subheader">
							{{mb_strtoupper($documentType->description, 'UTF-8')}} {{mb_strtoupper($issuer->code, 'UTF-8')}} N° {{sprintf('%03s', $document->number)}}<br>
							{{mb_strtoupper($issuer->city, 'UTF-8')}}, {{$issueDateStr}}
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
									{{mb_strtoupper($issuerSettings->operative_section_beginning, 'UTF-8')}} {{mb_strtoupper($documentType->action_in_operative_section, 'UTF-8')}}
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
									<p><b>ANEXO {{$hasAnexoUnico ? 'ÚNICO' : $intToRomanNumbers[$key+1]}}</b></p>
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
									Fdo. {{$issuerSettings->true_copy_signatory_full_name}} <br>
									{{$issuerSettings->true_copy_signatory_role}}
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
						Fdo. {{$issuerSettings->true_copy_signatory_full_name}} <br>
						{{$issuerSettings->true_copy_signatory_role}}
					</p>
				</div>
			</div>
		@endif
	</body>		
</html>