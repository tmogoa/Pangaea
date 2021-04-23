class Parser {
    renderable = "";

    getFeaturedImg(data) {
        let url = null;
        data.blocks.forEach((block) => {
            if (block.type === "image") {
                url = block.data.file.url;
            }
        });
        return url;
    }

    parse(data) {
        data.blocks.forEach((block) => {
            switch (block.type) {
                case "header":
                    this.parseHeader(block.data);
                    break;
                case "paragraph":
                    this.parseParagraph(block.data);
                    break;
                case "delimiter":
                    this.parseDelimiter();
                    break;
                case "image":
                    this.parseImage(block.data);
                    break;
                case "embed":
                    this.parseEmbed(block.data);
                    break;
                default:
                    console.log(
                        "Warning: unknown block type, skipping this block."
                    );
                    break;
            }
        });

        return this.renderable;
    }

    parseHeader(data) {
        this.renderable += `<h${data.level}>${data.text}</h${data.level}>`;
    }

    parseParagraph(data) {
        this.renderable += `<p>${data.text}</p>`;
    }

    parseDelimiter() {
        this.renderable += `<div class="text-3xl tracking-widest text-center text-gray-600">***</div>`;
    }

    parseImage(data) {
        const borderStyle = data.withBorder ? "border" : "";
        const widthStyle = data.stretched ? "w-11/12 mx-auto" : "";
        const backgroundStyle = data.withBackground
            ? "bg-gray-200 rounded"
            : "";

        this.renderable += `
        <div class="p-4 flex flex-col items-center ${borderStyle} ${widthStyle} ${backgroundStyle}">
            <img src="${data.file.url}"/>
            <span class="text-sm">${data.caption}</span>
        </div>`;
    }

    parseEmbed(data) {
        this.renderable += `
        <div class="flex flex-col items-center">
            <iframe width="${data.width}" height="${data.height}" src="${data.embed}"></iframe>
            <span class="text-sm mt-2">${data.caption}</span>
        </div>
        `;
    }
}
