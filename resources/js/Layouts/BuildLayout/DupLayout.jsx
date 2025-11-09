import Baselayout from "../BaseLayout";
import FootContent from "./Components/FootContent";
import TopContent from "./Components/TopContent";

const DupLayout = ({ children, topMenu }) => {
	return (
		<Baselayout>
			<TopContent />
			{children}
			<FootContent />
		</Baselayout>
	);
}

export default DupLayout;
