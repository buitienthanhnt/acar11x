import Baselayout from "../BaseLayout"
import TopContent from "./Components/TopContent"

const TopContentLayout = ({ children }) => {
	return (
		<Baselayout>
			<TopContent></TopContent>
			{children}
		</Baselayout>
	)
}

export default TopContentLayout