<template>
    <div>
        <h2>Week {{ week }} Matches</h2>
        <table>
            <thead>
            <tr>
                <th>Team 1</th>
                <th>Team 2</th>
                <th>Result</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="match in matches" :key="match.id">
                <td>{{ match.team_a.name }}</td>
                <td>{{ match.team_b.name }}</td>
                <td>{{ match.win_team_goals }}-{{ match.lost_team_goals }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        matches: Array,
        week: Number
    }
};
</script>
