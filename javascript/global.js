function showMessage(message, type) {
    let container = document.querySelector('.messages');

    if (!container) {
        container = document.createElement('div');
        container.className = 'container messages';
        document.body.insertBefore(container, document.body.children[1]);
    }

    const article = document.createElement('article');
    article.className = `message ${type}`;
    article.textContent = message;
    container.appendChild(article);

    setTimeout(() => {
        article.remove();
        if (container.children.length === 0) container.remove();
    }, 4000);
}

function encodeForAjax(data) {
    return Object.keys(data).map(
        k => encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    ).join('&')
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.message').forEach(msg => {
        setTimeout(() => {
            msg.remove();
            const container = document.querySelector('.messages');
            if (container && container.children.length === 0) container.remove();
        }, 4000);
    });
});