const BodyContent = ({ content, children, topChildren }: any) => {
    return (
        <div className="container mx-auto p-2">
            {topChildren ? <>
                {topChildren}
                <div className="h-1"></div>
                </> : null
            }
            {content}
            {children}
        </div>
    )
}

export default BodyContent;
