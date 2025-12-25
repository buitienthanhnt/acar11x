import { useMemo } from "react";
import { loadStripe } from '@stripe/stripe-js';
import {
	CheckoutProvider
} from '@stripe/react-stripe-js/checkout';
import SessionCheckoutForm from "./SessionCheckoutForm";
import axios from "axios";
import AppConfig from "@/Pages/Ahomeglobal/Assets/AppConfig";
import Urls from '../../../netWork/Urls';

// Make sure to call `loadStripe` outside of a component’s render to avoid
// recreating the `Stripe` object on every render.
// This is your test publishable API key.
const stripePromise = loadStripe(AppConfig.stripeKey);

const StripeSessionOnline = () => {
	const promise = useMemo(() => {
		return axios.post(Urls.payment, {
			paymentMethod: "stripe"
		}).then((res) => res.data.clientSecret);
	}, []);

	const appearance = {
		theme: 'stripe',
	};

	return (
		<CheckoutProvider
			stripe={stripePromise}
			options={{
				clientSecret: promise,
				// @ts-ignore
				elementsOptions: { appearance: appearance },
			}}
		>
			<SessionCheckoutForm></SessionCheckoutForm>
		</CheckoutProvider>
	)
}

export default StripeSessionOnline;