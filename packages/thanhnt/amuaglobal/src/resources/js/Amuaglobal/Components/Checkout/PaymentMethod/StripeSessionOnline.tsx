import { useMemo } from "react";
import { loadStripe } from '@stripe/stripe-js';
import {
	CheckoutProvider
} from '@stripe/react-stripe-js/checkout';
import SessionCheckoutForm from "./SessionCheckoutForm";
import axios from "axios";
import AppConfig from "@/Pages/Ahomeglobal/Assets/AppConfig";

const paymentUrl = '/checkout-payment';

// Make sure to call `loadStripe` outside of a component’s render to avoid
// recreating the `Stripe` object on every render.
// This is your test publishable API key.
const stripePromise = loadStripe(AppConfig.stripeKey);

const StripeSessionOnline = () => {
	const promise = useMemo(() => {
		/**
		 * gọi api tạo checkout session
		 * bằng cách checkout này sẽ hiển thị nút thanh toán(biểu mẫu) online mà không chuyển trang riêng biệt
		 */
		return axios.post(paymentUrl, {
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