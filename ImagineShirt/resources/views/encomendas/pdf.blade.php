<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<link rel="stylesheet" href="{{asset('css/pdf.css')}}">

	</head>

	<body>
		<div class="invoice-box" style="overflow-x: auto;">
			<table>
			<tr class="top">
	<td colspan="4">
		<table>
			<tr>
				<td class="title">
					<img src="{{ asset('img/logo.png') }}" style="width: 100%; max-width: 300px" />
				</td>
				@php    
                    switch($encomenda->status){
                    case 'pending':
                        $status = 'Pendente';
                        break;
                    case 'closed':
                        $status = 'Fechada';
                        break;
                    case 'paid':
                        $status = 'Paga';
                        break;
                    case 'canceled':
                        $status = 'Cancelada';
                        break;
                    }
                @endphp
				<td class="right-align">
					ID da Encomenda: {{ $encomenda->id }}<br />
					Criada a: {{$encomenda->date}}<br />
					Status: {{$status}}<br />
				</td>
			</tr>
		</table>
	</td>
</tr>

<tr class="information">
	<td colspan="4">
		<table>
			<tr>
				<td>
					ImagineShirt, Inc.<br />
					Rua General Norton de Matos<br />
					Apartado 4133,
					2411-901 Leiria – Portugal
					<br>Notas: {{ $encomenda->notes }}
				</td>

				<td class="right-align">
					{{ $encomenda->clientes->user->name }}<br />
					{{ $encomenda->clientes->user->address }}<br />
					NIF: {{ $encomenda->clientes->nif }}<br />
					Email: {{ $encomenda->clientes->user->email }}
				</td>
			</tr>
		</table>
	</td>
</tr>


				<tr class="heading">
					<td>Metodo de pagamento</td>
					<td></td>
					<td></td>
					<td class="right-align">
                    @if ($encomenda->payment_type == 'MC')
                        MasterCard
                    @else
                        {{ $encomenda->payment_type }}
                    @endif
                    </td>
				</tr>

				<tr class="details">
					<td>Referência pagamento</td>
					<td></td>
					<td></td>
					<td class="right-align">{{ $encomenda->payment_ref }}</td>
				</tr>

				<tr class="heading">
					<td>Item</td>
					<td>Preço unitário</td>
					<td class="right-align">Quantidade</td>
					<td class="right-align">Total</td>
				</tr>

				@php
                    $customer_tshirt = 0;
                    $catalogo_tshirt = 0;
                @endphp

                    @foreach ($encomenda->itensEncomenda as $item)
                        <tr class="item">
                            <td>{{ $item->tshirts->name }}</td>
                            <td>{{ $item->unit_price }}€</td>
							<td class="right-align">{{ $item->qty }}</td>
							<td class="right-align">{{ $item->sub_total}}€</td>
                        </tr>

					@if (!is_null($item->customer_id) && $item->qty >= $descontos['quantdesconto'])
                        @php
                            $customer_tshirt= $customer_tshirt + $item->qty;
                        @endphp
                    @endif

                    @if (is_null($item->customer_id) && $item->qty >= $descontos['quantdesconto'])
                        @php
                            $catalogo_tshirt = $catalogo_tshirt + $item->qty;
                        @endphp
                    @endif
					
                    @endforeach
					<tr class="total">
						<td></td>
						<td></td>
						<td></td>
						<td class="right-align"><span>
								Total s/ desconto: {{$encomenda->total_price + (($catalogo_tshirt * $descontos['descontocatalogo'])+($customer_tshirt * $descontos['descontoown']))}}€
							<br>Desconto: {{($catalogo_tshirt * $descontos['descontocatalogo'])+($customer_tshirt * $descontos['descontoown']) }} €
							<br>Total: {{ $encomenda->total_price }}€</span>
						</td>
                	</tr>
			</table>
		</div>
	</body>
</html>