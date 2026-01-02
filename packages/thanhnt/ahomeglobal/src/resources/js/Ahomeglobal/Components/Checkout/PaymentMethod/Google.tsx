import { Head, } from "@inertiajs/react";
import axios from "axios";
import { useEffect } from "react";

const baseCardPaymentMethod = {
	type: 'CARD',
	parameters: {
		allowedAuthMethods: ['PAN_ONLY', 'CRYPTOGRAM_3DS'],
		allowedCardNetworks: ['MASTERCARD', 'VISA']
	}
};

const googlePayClient = new google.payments.api.PaymentsClient({ environment: 'TEST' }); // Use 'PRODUCTION' for live

const Google = () => {

	const onGooglePayButtonClicked = () => {
		// Define payment request
		const paymentDataRequest = {
			apiVersion: 2,
			apiVersionMinor: 0,
			allowedPaymentMethods: [baseCardPaymentMethod],
			merchantInfo: {
				merchantName: 'ahome',
				merchantId: 'BCR2DN4TU7FMFN2X' // From Google Pay Console
			},
			transactionInfo: {
				totalPriceStatus: 'FINAL',
				totalPrice: '10.00',
				currencyCode: 'USD',
				countryCode: 'US',
			},
			paymentMethod: 'google'
		};

		googlePayClient.loadPaymentData(paymentDataRequest)
			.then(function (paymentData) {
				console.log('+++++++', paymentData);
				
				// Send token to Laravel backend
				axios.post('https://ahomeglobal.com/checkout',paymentData);
			});
	}

	useEffect(() => {
		const button = googlePayClient.createButton({ onClick: onGooglePayButtonClicked });
		document.getElementById('google-pay-button').appendChild(button);
	}, [])
	return (
		<div>
			<Head title="checkout page">
				
			</Head>
			<div>Google</div>
			<div id="google-pay-button"></div>
		</div>
	)
}


export default Google;