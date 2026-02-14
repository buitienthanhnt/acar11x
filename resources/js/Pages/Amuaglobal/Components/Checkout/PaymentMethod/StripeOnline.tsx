import { useEffect, useState } from "react";
import CheckoutForm from "./CheckoutForm";
import { loadStripe } from '@stripe/stripe-js';
import { Elements } from "@stripe/react-stripe-js";
import AppConfig from "../../../Assets/AppConfig";

const stripePromise = loadStripe(AppConfig.stripeKey);

/**
 * noe not support the ui_mode of this payment
 * only use checkoutSession payment or hosted(default redirect stripe payment)
 */
const StripeOnline = () => {
	const [clientSecret, setClientSecret] = useState("");

	useEffect(() => {
		// Create PaymentIntent as soon as the page loads
		fetch("/create-checkout-session", {
			method: "GET",
		})
			.then((res) => res.json())
			.then((data) => setClientSecret(data.clientSecret));
	}, []);

	const appearance = {
		theme: 'stripe',
	};

	const loader = 'auto';

	return (
		<div>
			{clientSecret && (
				// @ts-ignore
				<Elements options={{ clientSecret, appearance, loader }} stripe={stripePromise}>
					<CheckoutForm></CheckoutForm>
				</Elements>
			)}
		</div>
	);
};

export default StripeOnline;