import usePageProps from "./usePageProps";

const useMessage = (): { success?: string, error?: string } => {
	const props = usePageProps();

	return {
		success: props.messages,
		error: props.error,
	};
}

export default useMessage;