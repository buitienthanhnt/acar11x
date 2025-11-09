import usePageRandom from "@/hook/usePageRandom";
import { HorizonList } from "../PageComponent";

const RandomHorizon = ({data}) => {
	// const { pages, isLoading } = usePageRandom();
	// if (isLoading || !pages) {return null;}
	if (!data) {
		return null;
	}
	return (
		<HorizonList items={data}></HorizonList>
	)
}

export default RandomHorizon