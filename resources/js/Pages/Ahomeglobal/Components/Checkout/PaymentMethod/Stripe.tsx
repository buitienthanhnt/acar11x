import { useForm } from "@inertiajs/react";
import { Button, Spinner } from "@material-tailwind/react";
import AwaitProcess from "./AwaitProcess";

const Stripe = () => {
	const { post, data, processing } = useForm(
		{ paymentMethod: 'stripe', }
	);

	const handlePaypalCheckout = () => {
		post(route('checkout.payment'));
	}

	return (
		<div>
			<Button
				fullWidth
				variant="gradient"
				color="deep-purple"
				className="h-12 border-blue-500 focus:ring-blue-100/50 hover:bg-blue-100/50 "
				onClick={handlePaypalCheckout}
			>
				<img
					src="http://acar11x.dev/ahome/assets/stripe.png"
					className="mx-auto grid h-12 w-auto -translate-y-3 "
					alt="stripe"
				/>
			</Button>
			<AwaitProcess
				open={processing}
				headerContent="await loading for payment process"
				bodyContent={<Spinner className="h-12 w-12" />}>
			</AwaitProcess>
		</div>
	)
}

export default Stripe;