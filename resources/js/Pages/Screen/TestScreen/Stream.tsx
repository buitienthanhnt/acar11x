import { useCallback, useEffect, useState } from "react"

const Stream = () => {
	const [content, setContent] = useState<string>('');

	const streamContent = useCallback(async () => {
		const response = await fetch('/ahome/test/inertia-stream');
		const reader = response.body.getReader();
		const decoder = new TextDecoder();

		while (true) {
			const { done, value } = await reader.read();
			if (done) break;

			const chunk = decoder.decode(value, { stream: true });
			// Cập nhật state để hiển thị lên giao diện
			setContent((prevContent) => prevContent + chunk);
		}
	}, [])

	useEffect(() => {
		streamContent();
	}, [])

	return (
		<div>
			{content}
		</div>
	)
}

export default Stream