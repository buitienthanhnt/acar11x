import Baselayout from "../BaseLayout";
import FootContent from "./Components/FootContent";

export default function FootContentLayout({ children }) {
	return (
		<Baselayout>
			{children}
			<FootContent></FootContent>
		</Baselayout>
	)
}