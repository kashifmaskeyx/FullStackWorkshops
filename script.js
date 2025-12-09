const API_URL = 'http://localhost:3000/movies';
const movieListDiv = document.getElementById('movie-list');
const searchInput = document.getElementById('search-input');
const form = document.getElementById('add-movie-form');
const statusContainer = document.getElementById('status-container');

let allMovies = [];

function showStatus(message, isSuccess) {
  statusContainer.innerHTML = `
    <div class="status-message ${isSuccess ? 'status-success' : 'status-error'}">
      ${message}
    </div>
  `;
  setTimeout(() => {
    statusContainer.innerHTML = '';
  }, 3000);
}

function renderMovies(moviesToDisplay) {
  movieListDiv.innerHTML = '';

  if (moviesToDisplay.length === 0) {
    movieListDiv.innerHTML = '<div class="no-movies">No movies found matching your criteria.</div>';
    return;
  }

  moviesToDisplay.forEach(movie => {
    const movieElement = document.createElement('div');
    movieElement.classList.add('movie-item');

    movieElement.innerHTML = `
      <p><strong>${escapeHtml(movie.title)}</strong> (${movie.year}) - ${escapeHtml(movie.genre)}</p>
      <div class="movie-actions">
        <button onclick="editMoviePrompt(${movie.id}, '${escapeHtml(movie.title)}', ${movie.year}, '${escapeHtml(movie.genre)}')">Edit</button>
        <button onclick="deleteMovie(${movie.id})">Delete</button>
      </div>
    `;

    movieListDiv.appendChild(movieElement);
  });
}

function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

function fetchMovies() {
  fetch(API_URL)
    .then(response => {
      if (!response.ok) throw new Error('Failed to fetch movies');
      return response.json();
    })
    .then(movies => {
      allMovies = movies;
      renderMovies(allMovies);
    })
    .catch(error => {
      console.error('Error fetching movies:', error);
      movieListDiv.innerHTML = '<div class="no-movies">⚠️ Unable to connect to server. Make sure JSON Server is running on http://localhost:3000</div>';
    });
}

searchInput.addEventListener('input', function () {
  const searchTerm = searchInput.value.toLowerCase();

  const filteredMovies = allMovies.filter(movie => {
    const titleMatch = movie.title.toLowerCase().includes(searchTerm);
    const genreMatch = movie.genre.toLowerCase().includes(searchTerm);
    return titleMatch || genreMatch;
  });

  renderMovies(filteredMovies);
});

form.addEventListener('submit', function (event) {
  event.preventDefault();

  const newMovie = {
    title: document.getElementById('title').value,
    genre: document.getElementById('genre').value,
    year: parseInt(document.getElementById('year').value)
  };

  fetch(API_URL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(newMovie)
  })
    .then(response => {
      if (!response.ok) throw new Error('Failed to add movie');
      return response.json();
    })
    .then(() => {
      form.reset();
      fetchMovies();
      showStatus('✓ Movie added successfully!', true);
    })
    .catch(error => {
      console.error('Error adding movie:', error);
      showStatus('✗ Failed to add movie. Please try again.', false);
    });
});

function editMoviePrompt(id, currentTitle, currentYear, currentGenre) {
  const newTitle = prompt('Enter new Title:', currentTitle);
  if (newTitle === null) return;

  const newYearStr = prompt('Enter new Year:', currentYear);
  if (newYearStr === null) return;

  const newGenre = prompt('Enter new Genre:', currentGenre);
  if (newGenre === null) return;

  if (newTitle && newYearStr && newGenre) {
    const updatedMovie = {
      id: id,
      title: newTitle,
      year: parseInt(newYearStr),
      genre: newGenre
    };

    updateMovie(id, updatedMovie);
  }
}

function updateMovie(movieId, updatedMovieData) {
  fetch(`${API_URL}/${movieId}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(updatedMovieData)
  })
    .then(response => {
      if (!response.ok) throw new Error('Failed to update movie');
      return response.json();
    })
    .then(() => {
      fetchMovies();
      showStatus('✓ Movie updated successfully!', true);
    })
    .catch(error => {
      console.error('Error updating movie:', error);
      showStatus('✗ Failed to update movie. Please try again.', false);
    });
}

function deleteMovie(movieId) {
  if (!confirm('Are you sure you want to delete this movie?')) return;

  fetch(`${API_URL}/${movieId}`, {
    method: 'DELETE'
  })
    .then(response => {
      if (!response.ok) throw new Error('Failed to delete movie');
      fetchMovies();
      showStatus('✓ Movie deleted successfully!', true);
    })
    .catch(error => {
      console.error('Error deleting movie:', error);
      showStatus('✗ Failed to delete movie. Please try again.', false);
    });
}

fetchMovies();
