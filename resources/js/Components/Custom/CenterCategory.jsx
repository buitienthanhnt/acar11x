import GalleryList from "./GalleryList";

const CenterCategory = ({ name, data }) => {
	// const {data, isFetching} = useCenterCategory();
	// if (isFetching || !data) {
	// 	return;
	// }

	if (!data) {
		return null;
	}
	
	return (
		<GalleryList data={data}></GalleryList>
	)
}

export { CenterCategory };