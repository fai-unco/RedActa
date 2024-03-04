@php( $issuer = $document->issuer)
@php( $months = ["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"] )
@php( $issueDate = strtotime($document->issue_date) )
@php( $hasAnexoUnico = $document->has_anexo_unico )
@php( $body = json_decode($document->body) )
@php( $intToRomanNumbers = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X'])
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
							<img src="{{ env('STATIC_FILES_DIRECTORY').'/uploads/'.$document->heading->file->id.'.png' }}">
						</div>
						<div class="subheader">
							{{mb_strtoupper($document->documentType->description, 'UTF-8')}} {{mb_strtoupper($issuer->code, 'UTF-8')}} N°
							@if($document->number)
								{{sprintf('%03s', $document->number)}}
							@else
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							@endif 
							<br>
							{{mb_strtoupper($document->issuer->city, 'UTF-8')}},
							@if($document->issue_date)
								{{date('d', $issueDate)." de ".$months[date('n', $issueDate)-1]. " de ".date('Y', $issueDate)}}
							@else
								@for($i = 0; $i < 9; $i++)
									&nbsp;
								@endfor
							@endif 
						</div>
					</th>
				</tr>
			</thead>
			<tbody class="document-body">
				<tr>
					<td class="document-body-cell">
						<div class="visto-section indented">
							<b class="visto-section-beggining">VISTO</b>, {!! $body->visto !!}
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
									{{$document->operativeSectionBeginning ? mb_strtoupper($document->operativeSectionBeginning->content, 'UTF-8') : ''}} <br>
									{{mb_strtoupper($document->documentType->action_in_operative_section, 'UTF-8')}}:
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
						<div class="signatures-container">
							@foreach($document->signatures as $signature)
								<div class="stamp">
									{!! $signature->stamp->content !!}	
								</div>								
							@endforeach
						</div>
						@if($blankPageAtEnd)
							<div class="page-break"></div>
						@endif
						<div class="anexos-section">
							@foreach($anexos as $key=>$anexo)
								<div class="anexo-content">
									<p><b>ANEXO {{$hasAnexoUnico ? 'ÚNICO' : $intToRomanNumbers[$key+1]}}</b></p>
									@php($files = glob(env('STATIC_FILES_DIRECTORY').'/uploads/'.$anexo->file->id.'-*.png'))
									@foreach($files as $file)
										<img src="{{ $file }}" class="anexo-img">
									@endforeach
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
								<div class="stamp true-copy-stamp">
									{!! $document->trueCopyStamp->content !!}
								</div>
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
					<div class="stamp true-copy-stamp">
						{!! $document->trueCopyStamp->content !!}
					</div>
				</div>
			</div>
		@endif
	</body>		
</html>