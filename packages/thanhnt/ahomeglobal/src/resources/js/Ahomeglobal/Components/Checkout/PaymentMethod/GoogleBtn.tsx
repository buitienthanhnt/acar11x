import GooglePayButton from '@google-pay/button-react';

const GoogleBtn = () => {
	return (
		<div>
			<GooglePayButton
				environment="TEST"
				paymentRequest={{
					apiVersion: 2,
					apiVersionMinor: 0,
					allowedPaymentMethods: [
						{
							type: 'CARD',
							parameters: {
								allowedAuthMethods: ['PAN_ONLY', 'CRYPTOGRAM_3DS'],
								allowedCardNetworks: ['MASTERCARD', 'VISA'],
							},
							tokenizationSpecification: {
								type: 'PAYMENT_GATEWAY',
								parameters: {
									"gateway": "stripe",
									"stripe:version": "2025-12-15.clover",
									"stripe:publishableKey": "pk_test_51SgHKvECZlJBo2W8RBdbqdaLEqTmLdYcymTnQcx1X9PQQsTXbAlUT7c41vk0thAVwvmqPa3eIWBjWDtQlZZUvixb00dUJomgvi",
								},
							},
						},
					],
					merchantInfo: {
						merchantId: 'BCR2DN4TU7FMFN2X',
						merchantName: 'ahome',
					},
					transactionInfo: {
						totalPriceStatus: 'FINAL',
						totalPriceLabel: 'Total',
						totalPrice: '100.00',
						currencyCode: 'USD',
						countryCode: 'US',
					},
				}}
				onLoadPaymentData={paymentRequest => {
					console.log('load payment data', paymentRequest);
				}}
			/>;
		</div>
	)
}

export default GoogleBtn