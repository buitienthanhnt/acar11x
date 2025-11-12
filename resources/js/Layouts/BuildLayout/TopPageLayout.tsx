import DupLayout from "./DupLayout";
import BodyContent from "./Components/BodyContent";
import { TopPage } from "@/Components/PageComponent";

const TopPageLayout = ({ children, topMenu }) => {

	return (
		<DupLayout>
			<BodyContent
				topChildren={<TopPage></TopPage>}
				content={children}>
			</BodyContent>
		</DupLayout>
	);
}

export default TopPageLayout;
